<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BorrowingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Test route
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working',
        'version' => '1.0.0'
    ]);
});

// Tambahkan route GET untuk login page info
Route::get('/login', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working',
        'version' => '1.0.0'
    ]);
});

// Public routes (no authentication)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public book routes
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/search', [BookController::class, 'search']);
Route::get('/books/{id}', [BookController::class, 'show']);

// Public category routes
Route::get('/categories', [CategoryController::class, 'index']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    });

    // Borrowing routes
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    Route::post('/borrowings/{id}/return', [BorrowingController::class, 'return']);

    // Admin only routes - PERBAIKAN: gunakan middleware yang valid
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{id}', [BookController::class, 'update']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
    });
});

// Fallback route untuk 404
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route not found'
    ], 404);
});
