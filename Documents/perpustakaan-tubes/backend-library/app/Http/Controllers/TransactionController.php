<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = $request->only(['status', 'member_id', 'book_id', 'overdue']);

            $transactions = $this->transactionService->getAllTransactions($perPage, $filters);

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $transaction = $this->transactionService->getTransactionById($id);

            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function borrow(Request $request): JsonResponse
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $transaction = $this->transactionService->borrowBook($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dipinjam',
                'data' => $transaction
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function return(Request $request): JsonResponse
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'return_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $transaction = $this->transactionService->returnBook(
                $request->input('transaction_id'),
                $request->only(['return_date', 'notes'])
            );

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dikembalikan',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function overdue(): JsonResponse
    {
        try {
            $transactions = $this->transactionService->getOverdueTransactions();

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function memberTransactions(Request $request, $memberId): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $transactions = $this->transactionService->getMemberTransactions($memberId, $perPage);

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function extend(Request $request, $id): JsonResponse
    {
        $request->validate([
            'additional_days' => 'nullable|integer|min:1|max:14',
        ]);

        try {
            $additionalDays = $request->input('additional_days', 7);
            $transaction = $this->transactionService->extendDueDate($id, $additionalDays);

            return response()->json([
                'success' => true,
                'message' => 'Tanggal pengembalian berhasil diperpanjang',
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function statistics(): JsonResponse
    {
        try {
            $statistics = $this->transactionService->getTransactionStatistics();

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
