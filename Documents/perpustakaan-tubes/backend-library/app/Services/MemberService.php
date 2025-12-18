<?php

namespace App\Services;

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MemberService
{
    public function getAllMembers($perPage = 15, $filters = [])
    {
        $query = Member::with('user');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('full_name', 'like', "%{$filters['search']}%")
                  ->orWhere('member_code', 'like', "%{$filters['search']}%")
                  ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getMemberById($id)
    {
        return Member::with([
            'user',
            'transactions.book',
            'fines',
            'activeBorrows.book',
            'unpaidFines'
        ])->findOrFail($id);
    }

    public function createMember(array $data)
    {
        DB::beginTransaction();
        try {
            // Create user account
            $user = User::create([
                'name' => $data['full_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password123'),
            ]);

            // Upload photo if provided
            if (isset($data['photo'])) {
                $data['photo'] = $this->uploadPhoto($data['photo']);
            }

            // Create member
            $memberData = [
                'user_id' => $user->id,
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'join_date' => $data['join_date'] ?? Carbon::today(),
                'expiry_date' => $data['expiry_date'] ?? Carbon::today()->addYear(),
                'status' => $data['status'] ?? 'active',
                'photo' => $data['photo'] ?? null,
            ];

            $member = Member::create($memberData);

            DB::commit();
            return $member->load('user');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateMember($id, array $data)
    {
        DB::beginTransaction();
        try {
            $member = Member::findOrFail($id);

            if (isset($data['photo'])) {
                if ($member->photo) {
                    Storage::delete($member->photo);
                }
                $data['photo'] = $this->uploadPhoto($data['photo']);
            }

            $member->update($data);

            // Update user email if changed
            if (isset($data['email']) && $member->user) {
                $member->user->update(['email' => $data['email']]);
            }

            DB::commit();
            return $member->load('user');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteMember($id)
    {
        $member = Member::findOrFail($id);

        if ($member->activeBorrows()->count() > 0) {
            throw new \Exception('Tidak dapat menghapus member yang masih memiliki pinjaman aktif');
        }

        if ($member->unpaidFines()->count() > 0) {
            throw new \Exception('Tidak dapat menghapus member yang masih memiliki denda belum dibayar');
        }

        if ($member->photo) {
            Storage::delete($member->photo);
        }

        return $member->delete();
    }

    public function suspendMember($id, $reason = null)
    {
        $member = Member::findOrFail($id);
        $member->update([
            'status' => 'suspended',
            'notes' => $reason
        ]);

        return $member;
    }

    public function activateMember($id)
    {
        $member = Member::findOrFail($id);
        $member->update(['status' => 'active']);

        return $member;
    }

    public function getMemberStatistics($memberId)
    {
        $member = Member::findOrFail($memberId);

        return [
            'total_borrows' => $member->transactions()->count(),
            'active_borrows' => $member->activeBorrows()->count(),
            'total_returned' => $member->transactions()->returned()->count(),
            'total_fines' => $member->fines()->sum('fine_amount'),
            'unpaid_fines' => $member->total_unpaid_fines,
            'overdue_books' => $member->transactions()->overdue()->count(),
        ];
    }

    private function uploadPhoto($file)
    {
        return $file->store('member-photos', 'public');
    }
}
