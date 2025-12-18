<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FineService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FineController extends Controller
{
    protected $fineService;

    public function __construct(FineService $fineService)
    {
        $this->fineService = $fineService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = $request->only(['status', 'member_id', 'unpaid_only']);

            $fines = $this->fineService->getAllFines($perPage, $filters);

            return response()->json([
                'success' => true,
                'data' => $fines
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
            $fine = $this->fineService->getFineById($id);

            return response()->json([
                'success' => true,
                'data' => $fine
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function memberFines($memberId): JsonResponse
    {
        try {
            $fines = $this->fineService->getMemberFines($memberId);

            return response()->json([
                'success' => true,
                'data' => $fines
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function memberUnpaidFines($memberId): JsonResponse
    {
        try {
            $fines = $this->fineService->getMemberUnpaidFines($memberId);

            return response()->json([
                'success' => true,
                'data' => $fines
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pay(Request $request): JsonResponse
    {
        $request->validate([
            'fine_id' => 'required|exists:fines,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $fine = $this->fineService->payFine(
                $request->input('fine_id'),
                $request->input('amount')
            );

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda berhasil',
                'data' => $fine
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function payAll(Request $request, $memberId): JsonResponse
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $result = $this->fineService->payAllMemberFines(
                $memberId,
                $request->input('amount')
            );

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda berhasil',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function waive(Request $request, $id): JsonResponse
    {
        $request->validate([
            'reason' => 'nullable|string',
        ]);

        try {
            $fine = $this->fineService->waiveFine(
                $id,
                $request->input('reason')
            );

            return response()->json([
                'success' => true,
                'message' => 'Denda berhasil dihapuskan',
                'data' => $fine
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
            $statistics = $this->fineService->getFineStatistics();

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

    public function history(Request $request, $memberId): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $history = $this->fineService->getMemberFineHistory($memberId, $perPage);

            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
