<?php

namespace App\Services;

use App\Models\Fine;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FineService
{
    public function getAllFines($perPage = 15, $filters = [])
    {
        $query = Fine::with(['transaction.book', 'member']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (isset($filters['unpaid_only']) && $filters['unpaid_only']) {
            $query->unpaid();
        }

        return $query->orderBy('fine_date', 'desc')->paginate($perPage);
    }

    public function getFineById($id)
    {
        return Fine::with(['transaction.book', 'member'])
            ->findOrFail($id);
    }

    public function getMemberFines($memberId)
    {
        return Fine::with(['transaction.book'])
            ->where('member_id', $memberId)
            ->orderBy('fine_date', 'desc')
            ->get();
    }

    public function getMemberUnpaidFines($memberId)
    {
        return Fine::with(['transaction.book'])
            ->where('member_id', $memberId)
            ->unpaid()
            ->get();
    }

    public function payFine($fineId, $amount)
    {
        DB::beginTransaction();
        try {
            $fine = Fine::findOrFail($fineId);

            if ($fine->status === 'paid') {
                throw new \Exception('Denda sudah lunas');
            }

            $remainingAmount = $fine->remaining_amount;

            if ($amount > $remainingAmount) {
                throw new \Exception('Jumlah pembayaran melebihi sisa denda');
            }

            $fine->paid_amount += $amount;

            if ($amount === $remainingAmount) {
                $fine->paid_date = Carbon::today();
            }

            $fine->updateStatus();

            DB::commit();
            return $fine->fresh(['transaction.book', 'member']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function payAllMemberFines($memberId, $totalAmount = null)
    {
        DB::beginTransaction();
        try {
            $member = Member::findOrFail($memberId);
            $unpaidFines = $member->unpaidFines()->orderBy('fine_date')->get();

            if ($unpaidFines->isEmpty()) {
                throw new \Exception('Tidak ada denda yang belum dibayar');
            }

            $totalUnpaid = $unpaidFines->sum(function ($fine) {
                return $fine->remaining_amount;
            });

            $amountToPay = $totalAmount ?? $totalUnpaid;

            if ($amountToPay > $totalUnpaid) {
                throw new \Exception('Jumlah pembayaran melebihi total denda');
            }

            $remainingPayment = $amountToPay;

            foreach ($unpaidFines as $fine) {
                if ($remainingPayment <= 0) {
                    break;
                }

                $fineRemaining = $fine->remaining_amount;
                $paymentForThisFine = min($remainingPayment, $fineRemaining);

                $fine->paid_amount += $paymentForThisFine;

                if ($fine->paid_amount >= $fine->fine_amount) {
                    $fine->paid_date = Carbon::today();
                }

                $fine->updateStatus();
                $remainingPayment -= $paymentForThisFine;
            }

            DB::commit();
            return [
                'paid_amount' => $amountToPay,
                'remaining_fines' => $totalUnpaid - $amountToPay,
                'fines' => $unpaidFines->fresh()
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function waiveFine($fineId, $reason = null)
    {
        DB::beginTransaction();
        try {
            $fine = Fine::findOrFail($fineId);

            if ($fine->status === 'paid') {
                throw new \Exception('Denda sudah lunas');
            }

            $fine->paid_amount = $fine->fine_amount;
            $fine->paid_date = Carbon::today();
            $fine->notes = "Denda dihapuskan. Alasan: " . ($reason ?? 'Tidak ada alasan');
            $fine->updateStatus();

            DB::commit();
            return $fine->fresh(['transaction.book', 'member']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getFineStatistics()
    {
        return [
            'total_fines' => Fine::sum('fine_amount'),
            'total_paid' => Fine::sum('paid_amount'),
            'total_unpaid' => Fine::unpaid()->sum(DB::raw('fine_amount - paid_amount')),
            'count_unpaid' => Fine::unpaid()->count(),
            'count_paid' => Fine::paid()->count(),
        ];
    }

    public function getMemberFineHistory($memberId, $perPage = 15)
    {
        return Fine::with(['transaction.book'])
            ->where('member_id', $memberId)
            ->orderBy('fine_date', 'desc')
            ->paginate($perPage);
    }
}
