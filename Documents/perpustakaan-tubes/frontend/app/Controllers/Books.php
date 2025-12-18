<?php

namespace App\Controllers;

class Books extends BaseController
{
    protected $apiUrl;

    public function __construct()
    {
        // Pastikan helper api sudah di-load
        helper('api');
        
        // Ambil API URL dari .env
        $this->apiUrl = getenv('app.apiURL') ?: 'http://localhost:8000/api/';
    }

    public function index()
    {
        // ===== DATA DUMMY UNTUK TESTING LAYOUT DULU =====
        // Hapus/komen bagian ini setelah layout berhasil
        
        $dummyBooks = [
            [
                'id' => 1,
                'title' => 'Laravel: Up and Running',
                'author' => 'Matt Stauffer',
                'cover_image' => 'covers/laravel.jpg',
                'available_stock' => 5,
                'category' => 'Programming',
                'description' => 'Buku panduan lengkap Laravel',
                'isbn' => '978-1492041214',
                'publisher' => 'O\'Reilly Media'
            ],
            [
                'id' => 2,
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'cover_image' => null,
                'available_stock' => 3,
                'category' => 'Programming',
                'description' => 'Handbook of Agile Software Craftsmanship',
                'isbn' => '978-0132350884',
                'publisher' => 'Prentice Hall'
            ],
            [
                'id' => 3,
                'title' => 'Harry Potter and the Sorcerer\'s Stone',
                'author' => 'J.K. Rowling',
                'cover_image' => 'covers/harry.jpg',
                'available_stock' => 0,
                'category' => 'Fiction',
                'description' => 'Novel fantasi pertama seri Harry Potter',
                'isbn' => '978-0439708180',
                'publisher' => 'Scholastic'
            ],
            [
                'id' => 4,
                'title' => 'The Pragmatic Programmer',
                'author' => 'David Thomas & Andrew Hunt',
                'cover_image' => 'covers/pragmatic.jpg',
                'available_stock' => 7,
                'category' => 'Programming',
                'description' => 'Your journey to mastery',
                'isbn' => '978-0201616224',
                'publisher' => 'Addison-Wesley'
            ],
        ];

        $dummyCategories = ['Programming', 'Fiction', 'Science', 'Business', 'History'];

        $data = [
            'title' => 'Katalog Buku',
            'books' => $dummyBooks,
            'pagination' => [
                'current_page' => 1,
                'last_page' => 3,
                'total' => 25,
                'per_page' => 12
            ],
            'categories' => $dummyCategories,
            'currentSearch' => '',
            'currentCategory' => '',
            'apiStatus' => 'connected',
            'apiMessage' => 'Using dummy data for testing',
            'apiUrl' => $this->apiUrl
        ];

        return view('books/index', $data);
        // ===== END DATA DUMMY =====

        /* 
        // ===== KODE ASLI UNTUK CONNECT KE API (Pakai nanti) =====
        $page = $this->request->getGet('page') ?? 1;
        $search = $this->request->getGet('search') ?? '';
        $category = $this->request->getGet('category') ?? '';

        $params = [
            'page' => $page,
            'per_page' => 12
        ];
        
        if ($search) {
            $params['search'] = $search;
        }
        if ($category) {
            $params['category'] = $category;
        }

        // Get books from API
        $booksResponse = api_request('GET', $this->apiUrl . 'books', $params);
        
        // Get categories
        $categoriesResponse = api_request('GET', $this->apiUrl . 'categories');

        // Format books data
        $formattedBooks = [];
        if ($booksResponse['success'] && !empty($booksResponse['data'])) {
            $booksData = $booksResponse['data'];
            
            // Handle paginated response
            if (isset($booksData['data']) && is_array($booksData['data'])) {
                $booksArray = $booksData['data'];
            } else {
                $booksArray = is_array($booksData) ? $booksData : [];
            }
            
            foreach ($booksArray as $book) {
                $formattedBooks[] = [
                    'id' => $book['id'] ?? 0,
                    'title' => $book['title'] ?? 'Tidak ada judul',
                    'author' => $book['author'] ?? 'Tidak diketahui',
                    'cover_image' => $book['cover_image'] ?? null,
                    'available_stock' => $book['stock'] ?? $book['available_stock'] ?? 0,
                    'category' => $book['category'] ?? 'Umum',
                    'description' => $book['description'] ?? '',
                    'isbn' => $book['isbn'] ?? '',
                    'publisher' => $book['publisher'] ?? ''
                ];
            }
        }

        $data = [
            'title' => 'Katalog Buku',
            'books' => $formattedBooks,
            'pagination' => $booksResponse['success'] ? ($booksResponse['data'] ?? []) : [],
            'categories' => $categoriesResponse['success'] ? ($categoriesResponse['data'] ?? []) : [],
            'currentSearch' => $search,
            'currentCategory' => $category,
            'apiStatus' => $booksResponse['success'] ? 'connected' : 'disconnected',
            'apiMessage' => $booksResponse['message'] ?? '',
            'apiUrl' => $this->apiUrl
        ];

        return view('books/index', $data);
        // ===== END KODE API =====
        */
    }

    public function show($id)
    {
        // Data dummy untuk detail buku
        $dummyBook = [
            'id' => $id,
            'title' => 'Laravel: Up and Running',
            'author' => 'Matt Stauffer',
            'cover_image' => 'covers/laravel.jpg',
            'stock' => 5,
            'available_stock' => 5,
            'category' => 'Programming',
            'description' => 'This book is a practical guide to the Laravel web framework. You\'ll build a complete application from the ground up, learning how to build, test, and deploy Laravel applications.',
            'isbn' => '978-1492041214',
            'publisher' => 'O\'Reilly Media',
            'publication_year' => 2020,
            'created_at' => '2024-01-15 10:30:00',
            'updated_at' => '2024-01-15 10:30:00'
        ];

        $data = [
            'title' => $dummyBook['title'] . ' - Detail Buku',
            'book' => $dummyBook,
            'apiUrl' => $this->apiUrl
        ];

        return view('books/show', $data);
    }

    public function search()
    {
        $query = $this->request->getGet('q') ?? '';
        
        if (empty($query)) {
            return redirect()->to('/books');
        }

        // Data dummy untuk search
        $dummyBooks = [
            [
                'id' => 1,
                'title' => 'Laravel: Up and Running',
                'author' => 'Matt Stauffer',
                'cover_image' => 'covers/laravel.jpg',
                'available_stock' => 5,
                'category' => 'Programming'
            ]
        ];

        $data = [
            'title' => 'Hasil Pencarian: ' . $query,
            'books' => $dummyBooks,
            'query' => $query,
            'apiMessage' => 'Showing dummy search results',
            'apiUrl' => $this->apiUrl
        ];

        return view('books/search', $data);
    }

    public function recommendations()
    {
        // Data dummy untuk rekomendasi
        $dummyBooks = [
            [
                'id' => 1,
                'title' => 'Laravel: Up and Running',
                'author' => 'Matt Stauffer',
                'cover_image' => 'covers/laravel.jpg',
                'available_stock' => 5,
                'category' => 'Programming'
            ],
            [
                'id' => 2,
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'cover_image' => null,
                'available_stock' => 3,
                'category' => 'Programming'
            ],
            [
                'id' => 4,
                'title' => 'The Pragmatic Programmer',
                'author' => 'David Thomas & Andrew Hunt',
                'cover_image' => 'covers/pragmatic.jpg',
                'available_stock' => 7,
                'category' => 'Programming'
            ],
        ];

        $data = [
            'title' => 'Rekomendasi Buku',
            'books' => $dummyBooks,
            'apiMessage' => 'Showing dummy recommendations',
            'apiUrl' => $this->apiUrl
        ];

        return view('books/recommendations', $data);
    }

    public function borrow($bookId)
    {
        // Untuk testing, redirect dengan pesan
        session()->setFlashdata('success', 'Buku berhasil dipinjam! (Simulasi)');
        return redirect()->to('/books');
    }

    public function test()
    {
        $response = api_request('GET', $this->apiUrl);
        
        return $this->response->setJSON([
            'timestamp' => date('Y-m-d H:i:s'),
            'api_url' => $this->apiUrl,
            'response' => $response
        ]);
    }

    public function testBooks()
    {
        $response = api_request('GET', $this->apiUrl . 'books');
        
        return $this->response->setJSON([
            'timestamp' => date('Y-m-d H:i:s'),
            'endpoint' => $this->apiUrl . 'books',
            'response' => $response
        ]);
    }

    public function debug()
    {
        echo '<!DOCTYPE html><html><head><title>Debug Info</title><style>body{font-family:monospace;padding:20px;}</style></head><body>';
        echo '<h1>🔧 Debug Information - Library System</h1>';
        
        echo '<h2>1. Environment Variables</h2>';
        echo '<pre>';
        echo 'API URL from env: ' . getenv('app.apiURL') . "\n";
        echo 'Current API URL: ' . $this->apiUrl . "\n";
        echo 'Base URL: ' . base_url() . "\n";
        echo '</pre>';
        
        echo '<h2>2. Helper Functions Check</h2>';
        echo '<pre>';
        echo 'api_request() exists: ' . (function_exists('api_request') ? '✅ YES' : '❌ NO') . "\n";
        echo 'base_url() exists: ' . (function_exists('base_url') ? '✅ YES' : '❌ NO') . "\n";
        echo '</pre>';
        
        echo '<h2>3. Test API Connection</h2>';
        $testResponse = api_request('GET', $this->apiUrl);
        echo '<pre>';
        print_r($testResponse);
        echo '</pre>';
        
        echo '<h2>4. Test Books API</h2>';
        $booksResponse = api_request('GET', $this->apiUrl . 'books');
        echo '<pre>';
        print_r($booksResponse);
        echo '</pre>';
        
        echo '<h2>5. Session Data</h2>';
        echo '<pre>';
        print_r(session()->get());
        echo '</pre>';
        
        echo '</body></html>';
        die();
    }
}