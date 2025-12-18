<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'member_id',
        'days_late',
        'fine_amount',
        'paid_amount',
        'status',
        'fine_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'fine_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'fine_date' => 'date',
        'paid_date' => 'date',
    ];

    const FINE_PER_DAY = 1000; // Rp 1.000 per hari

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->fine_amount - $this->paid_amount;
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'partial']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public static function calculateFine($daysLate)
    {
        return $daysLate * self::FINE_PER_DAY;
    }

    public function updateStatus()
    {
        if ($this->paid_amount >= $this->fine_amount) {
            $this->status = 'paid';
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'unpaid';
        }
        $this->save();
    }
}
