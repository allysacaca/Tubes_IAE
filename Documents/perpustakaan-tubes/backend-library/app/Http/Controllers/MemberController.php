<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = $request->only(['status', 'search']);

            $members = $this->memberService->getAllMembers($perPage, $filters);

            return response()->json([
                'success' => true,
                'data' => $members
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
            $member = $this->memberService->getMemberById($id);

            return response()->json([
                'success' => true,
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'join_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        try {
            $member = $this->memberService->createMember($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil ditambahkan',
                'data' => $member
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:members,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'join_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);

        try {
            $member = $this->memberService->updateMember($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil diupdate',
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->memberService->deleteMember($id);

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function suspend(Request $request, $id): JsonResponse
    {
        try {
            $reason = $request->input('reason');
            $member = $this->memberService->suspendMember($id, $reason);

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil disuspend',
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function activate($id): JsonResponse
    {
        try {
            $member = $this->memberService->activateMember($id);

            return response()->json([
                'success' => true,
                'message' => 'Member berhasil diaktifkan',
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function statistics($id): JsonResponse
    {
        try {
            $statistics = $this->memberService->getMemberStatistics($id);

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
