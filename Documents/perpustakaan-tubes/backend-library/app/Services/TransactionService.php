<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Book;
use App\Models\Member;
use App\Models\Fine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionService
{
    const BORROW_DURATION_DAYS = 14; // 2 minggu

    public function getAllTransactions($perPage = 15, $filters = [])
    {
        $query = Transaction::with(['member', 'book', 'fine']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (isset($filters['book_id'])) {
            $query->where('book_id', $filters['book_id']);
        }

        if (isset($filters['overdue']) && $filters['overdue']) {
            $query->overdue();
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getTransactionById($id)
    {
        return Transaction::with(['member', 'book', 'fine'])
            ->findOrFail($id);
    }

    public function borrowBook(array $data)
    {
        DB::beginTransaction();
        try {
            // Validasi member
            $member = Member::findOrFail($data['member_id']);

            if ($member->status !== 'active') {
                throw new \Exception('Member tidak aktif');
            }

            // Cek denda yang belum dibayar
            if ($member->total_unpaid_fines > 0) {
                throw new \Exception('Member memiliki denda yang belum dibayar sebesar Rp ' . number_format($member->total_unpaid_fines, 0, ',', '.'));
            }

            // Cek limit peminjaman (maksimal 3 buku)
            $activeBorrows = $member->activeBorrows()->count();
            if ($activeBorrows >= 3) {
                throw new \Exception('Member sudah mencapai limit peminjaman maksimal (3 buku)');
            }

            // Validasi buku
            $book = Book::findOrFail($data['book_id']);

            if ($book->available_stock <= 0) {
                throw new \Exception('Stok buku tidak tersedia');
            }

            // Cek apakah member sudah meminjam buku yang sama
            $existingBorrow = Transaction::where('member_id', $member->id)
                ->where('book_id', $book->id)
                ->whereIn('status', ['borrowed', 'overdue'])
                ->first();

            if ($existingBorrow) {
                throw new \Exception('Member sudah meminjam buku ini');
            }

            // Buat transaksi
            $borrowDate = Carbon::parse($data['borrow_date'] ?? Carbon::today());
            $dueDate = $borrowDate->copy()->addDays(self::BORROW_DURATION_DAYS);

            $transaction = Transaction::create([
                'member_id' => $member->id,
                'book_id' => $book->id,
                'borrow_date' => $borrowDate,
                'due_date' => $dueDate,
                'status' => 'borrowed',
                'notes' => $data['notes'] ?? null,
            ]);

            // Update stok buku
            $book->updateAvailableStock();

            DB::commit();
            return $transaction->load(['member', 'book']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function returnBook($transactionId, array $data = [])
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::with(['member', 'book'])
                ->findOrFail($transactionId);

            if ($transaction->status === 'returned') {
                throw new \Exception('Buku sudah dikembalikan sebelumnya');
            }

            $returnDate = Carbon::parse($data['return_date'] ?? Carbon::today());
            $transaction->return_date = $returnDate;
            $transaction->status = 'returned';
            $transaction->notes = $data['notes'] ?? $transaction->notes;
            $transaction->save();

            // Update stok buku
            $transaction->book->updateAvailableStock();

            // Cek dan buat denda jika terlambat
            $daysLate = $transaction->days_late;

            if ($daysLate > 0) {
                $fineAmount = Fine::calculateFine($daysLate);

                Fine::create([
                    'transaction_id' => $transaction->id,
                    'member_id' => $transaction->member_id,
                    'days_late' => $daysLate,
                    'fine_amount' => $fineAmount,
                    'fine_date' => $returnDate,
                    'status' => 'unpaid',
                ]);
            }

            DB::commit();
            return $transaction->load(['member', 'book', 'fine']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getOverdueTransactions()
    {
        return Transaction::overdue()
            ->with(['member', 'book'])
            ->get()
            ->map(function ($transaction) {
                $transaction->current_days_late = $transaction->current_days_late;
                $transaction->potential_fine = Fine::calculateFine($transaction->current_days_late);
                return $transaction;
            });
    }

    public function getMemberTransactions($memberId, $perPage = 15)
    {
        return Transaction::with(['book', 'fine'])
            ->where('member_id', $memberId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function extendDueDate($transactionId, $additionalDays = 7)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction->status !== 'borrowed') {
                throw new \Exception('Hanya transaksi dengan status dipinjam yang dapat diperpanjang');
            }

            // Cek apakah sudah lewat due date
            if (Carbon::today()->gt($transaction->due_date)) {
                throw new \Exception('Tidak dapat memperpanjang buku yang sudah terlambat');
            }

            $transaction->due_date = Carbon::parse($transaction->due_date)->addDays($additionalDays);
            $transaction->save();

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getTransactionStatistics()
    {
        return [
            'total_transactions' => Transaction::count(),
            'active_borrows' => Transaction::borrowed()->count(),
            'overdue_borrows' => Transaction::overdue()->count(),
            'returned_today' => Transaction::returned()
                ->whereDate('return_date', Carbon::today())
                ->count(),
            'borrowed_today' => Transaction::whereDate('borrow_date', Carbon::today())
                ->count(),
        ];
    }
}
