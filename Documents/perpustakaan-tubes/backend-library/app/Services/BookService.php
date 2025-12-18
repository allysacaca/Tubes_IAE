<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookService
{
    /**
     * Get all books with pagination and filters
     */
    public function getAllBooks(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        try {
            $query = Book::query();

            // Filter by search
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('author', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
                });
            }

            // Filter by category NAME (not category_id)
            if (!empty($filters['category'])) {
                $query->where('category', $filters['category']);
            }

            // Filter by availability
            if (!empty($filters['available_only']) && $filters['available_only'] == 'true') {
                $query->where('stock', '>', 0);
            }

            return $query->orderBy('created_at', 'desc')->paginate($perPage);

        } catch (\Exception $e) {
            Log::error('Error in getAllBooks: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Search books
     */
    public function searchBooks(string $search)
    {
        try {
            return Book::where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get();

        } catch (\Exception $e) {
            Log::error('Error in searchBooks: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get book by ID
     */
    public function getBookById(int $id): Book
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                throw new \Exception('Book not found');
            }

            return $book;

        } catch (\Exception $e) {
            Log::error('Error in getBookById: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create new book
     */
    public function createBook(array $data): Book
    {
        try {
            // Handle cover image upload
            if (isset($data['cover_image']) && $data['cover_image'] instanceof \Illuminate\Http\UploadedFile) {
                $data['cover_image'] = $this->uploadCoverImage($data['cover_image']);
            }

            return Book::create($data);

        } catch (\Exception $e) {
            Log::error('Error in createBook: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update book
     */
    public function updateBook(int $id, array $data): Book
    {
        try {
            $book = $this->getBookById($id);

            // Handle cover image upload
            if (isset($data['cover_image']) && $data['cover_image'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old image
                if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                $data['cover_image'] = $this->uploadCoverImage($data['cover_image']);
            }

            $book->update($data);
            return $book->fresh();

        } catch (\Exception $e) {
            Log::error('Error in updateBook: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete book
     */
    public function deleteBook(int $id): void
    {
        try {
            $book = $this->getBookById($id);

            // Delete cover image
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $book->delete();

        } catch (\Exception $e) {
            Log::error('Error in deleteBook: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get book recommendations
     */
    public function getRecommendations(int $limit = 6)
    {
        try {
            return Book::where('stock', '>', 0)
                ->inRandomOrder()
                ->limit($limit)
                ->get();

        } catch (\Exception $e) {
            Log::error('Error in getRecommendations: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        try {
            return Book::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->pluck('category');

        } catch (\Exception $e) {
            Log::error('Error in getCategories: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Upload cover image
     */
    private function uploadCoverImage($file): string
    {
        try {
            return $file->store('covers', 'public');

        } catch (\Exception $e) {
            Log::error('Error in uploadCoverImage: ' . $e->getMessage());
            throw new \Exception('Failed to upload cover image');
        }
    }
}
