<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'member_code',
        'full_name',
        'email',
        'phone',
        'address',
        'join_date',
        'expiry_date',
        'status',
        'photo',
    ];

    protected $casts = [
        'join_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function activeBorrows()
    {
        return $this->hasMany(Transaction::class)
            ->whereIn('status', ['borrowed', 'overdue']);
    }

    public function unpaidFines()
    {
        return $this->hasMany(Fine::class)
            ->whereIn('status', ['unpaid', 'partial']);
    }

    public function getTotalUnpaidFinesAttribute()
    {
        return $this->unpaidFines()
            ->sum(\DB::raw('fine_amount - paid_amount'));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (empty($member->member_code)) {
                $member->member_code = 'MBR' . date('Ymd') . str_pad(Member::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
