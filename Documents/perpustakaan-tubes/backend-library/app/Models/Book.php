<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'publisher',
        'publication_year',
        'category',
        'category_id',
        'description',
        'stock',
        'cover_image',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['available'];

    /**
     * Get availability status
     */
    public function getAvailableAttribute(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get the category that owns the book
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all borrowings for the book
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
