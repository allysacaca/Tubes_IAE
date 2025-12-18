<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_code',
        'member_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function fine()
    {
        return $this->hasOne(Fine::class);
    }

    public function getDaysLateAttribute()
    {
        if ($this->status !== 'returned' || !$this->return_date) {
            return 0;
        }

        $dueDate = Carbon::parse($this->due_date);
        $returnDate = Carbon::parse($this->return_date);

        return $returnDate->gt($dueDate) ? $returnDate->diffInDays($dueDate) : 0;
    }

    public function getCurrentDaysLateAttribute()
    {
        if ($this->status === 'returned') {
            return 0;
        }

        $dueDate = Carbon::parse($this->due_date);
        $today = Carbon::today();

        return $today->gt($dueDate) ? $today->diffInDays($dueDate) : 0;
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'borrowed')
            ->where('due_date', '<', Carbon::today());
    }

    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_code)) {
                $transaction->transaction_code = 'TRX' . date('Ymd') . str_pad(Transaction::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
