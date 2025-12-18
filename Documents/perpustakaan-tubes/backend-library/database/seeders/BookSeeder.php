<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'isbn' => '978-0-13-468599-1',
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2008,
                'category' => 'Programming',
                'description' => 'Buku tentang penulisan kode yang bersih dan mudah dipelihara',
                'stock' => 5,
                'available_stock' => 5,
            ],
            [
                'isbn' => '978-0-321-12521-7',
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'publisher' => 'Addison-Wesley',
                'publication_year' => 1994,
                'category' => 'Programming',
                'description' => 'Buku klasik tentang design patterns dalam pemrograman berorientasi objek',
                'stock' => 3,
                'available_stock' => 3,
            ],
            [
                'isbn' => '978-0-596-52068-7',
                'title' => 'JavaScript: The Good Parts',
                'author' => 'Douglas Crockford',
                'publisher' => 'O\'Reilly Media',
                'publication_year' => 2008,
                'category' => 'Web Development',
                'description' => 'Panduan tentang fitur-fitur terbaik dari JavaScript',
                'stock' => 4,
                'available_stock' => 4,
            ],
            [
                'isbn' => '978-1-491-91050-2',
                'title' => 'Designing Data-Intensive Applications',
                'author' => 'Martin Kleppmann',
                'publisher' => 'O\'Reilly Media',
                'publication_year' => 2017,
                'category' => 'Database',
                'description' => 'Buku tentang sistem database dan aplikasi berbasis data',
                'stock' => 3,
                'available_stock' => 3,
            ],
            [
                'isbn' => '978-0-13-235088-4',
                'title' => 'The Clean Coder: A Code of Conduct for Professional Programmers',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2011,
                'category' => 'Programming',
                'description' => 'Panduan profesionalisme dalam pengembangan software',
                'stock' => 4,
                'available_stock' => 4,
            ],
            [
                'isbn' => '978-0-13-468684-4',
                'title' => 'Refactoring: Improving the Design of Existing Code',
                'author' => 'Martin Fowler',
                'publisher' => 'Addison-Wesley',
                'publication_year' => 2018,
                'category' => 'Programming',
                'description' => 'Teknik refactoring untuk meningkatkan kualitas kode',
                'stock' => 5,
                'available_stock' => 5,
            ],
            [
                'isbn' => '978-0-135-95705-9',
                'title' => 'The Pragmatic Programmer',
                'author' => 'David Thomas, Andrew Hunt',
                'publisher' => 'Addison-Wesley',
                'publication_year' => 2019,
                'category' => 'Programming',
                'description' => 'Panduan praktis untuk programmer profesional',
                'stock' => 6,
                'available_stock' => 6,
            ],
            [
                'isbn' => '978-1-59327-928-8',
                'title' => 'Eloquent JavaScript: A Modern Introduction',
                'author' => 'Marijn Haverbeke',
                'publisher' => 'No Starch Press',
                'publication_year' => 2018,
                'category' => 'Web Development',
                'description' => 'Pengenalan modern terhadap pemrograman JavaScript',
                'stock' => 4,
                'available_stock' => 4,
            ],
            [
                'isbn' => '978-1-492-05274-5',
                'title' => 'Learning PHP, MySQL & JavaScript',
                'author' => 'Robin Nixon',
                'publisher' => 'O\'Reilly Media',
                'publication_year' => 2021,
                'category' => 'Web Development',
                'description' => 'Panduan lengkap pengembangan web dengan PHP, MySQL dan JavaScript',
                'stock' => 5,
                'available_stock' => 5,
            ],
            [
                'isbn' => '978-1-61729-484-2',
                'title' => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'publisher' => 'O\'Reilly Media',
                'publication_year' => 2019,
                'category' => 'Web Development',
                'description' => 'Panduan komprehensif framework Laravel',
                'stock' => 4,
                'available_stock' => 4,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
