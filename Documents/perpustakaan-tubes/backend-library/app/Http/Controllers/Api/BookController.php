<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $filters = $request->only(['search', 'category', 'available_only']);

            $books = $this->bookService->getAllBooks($perPage, $filters);

            return response()->json([
                'success' => true,
                'message' => 'Books retrieved successfully',
                'data' => $books
            ]);
        } catch (\Exception $e) {
            \Log::error('BookController index error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving books: ' . $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
       try {
            $search = $request->input('q', '');

            if (empty($search)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Please provide search query',
                    'data' => []
                ]);
            }

            $books = $this->bookService->searchBooks($search);

            return response()->json([
                'success' => true,
                'message' => 'Search results',
                'data' => $books
            ]);
        } catch (\Exception $e) {
            \Log::error('BookController search error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error searching books'
            ], 500);
    }
}

    public function show($id): JsonResponse
    {
        try {
            $book = $this->bookService->getBookById($id);

            return response()->json([
                'success' => true,
                'message' => 'Book details',
                'data' => $book
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
            'isbn' => 'required|unique:books,isbn',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $book = $this->bookService->createBook($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $book
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
            'isbn' => 'sometimes|required|unique:books,isbn,' . $id,
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'stock' => 'sometimes|required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $book = $this->bookService->updateBook($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil diupdate',
                'data' => $book
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
            $this->bookService->deleteBook($id);

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function recommendations(): JsonResponse
    {
        try {
            $books = $this->bookService->getRecommendations();

            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function categories(): JsonResponse
    {
        try {
            $categories = $this->bookService->getCategories();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
