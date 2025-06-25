<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Book;

class BulkBookSync extends Command
{
    protected $signature = 'books:sync:bulk';
    protected $description = 'Import a large number of books from Google Books API by keywords';

    public function handle()
    {
        $keywords = [
            'fiction', 'fantasy', 'romance', 'thriller', 'science',
            'history', 'mystery', 'horror', 'philosophie', 'aventure',
            'poésie', 'biographie', 'économie', 'psychologie',
            'bande dessinée', 'roman graphique', 'technologie',
            'éducation', 'santé', 'développement personnel',
            'jeunesse', 'ado', 'voyage', 'cuisine',
            'art', 'musique', 'religion', 'sport', 'nature'
        ];

        $maxPages = 10; // 10 * 40 = 400 livres par mot-clé

        foreach ($keywords as $keyword) {
            $this->info("🔍 Syncing books for keyword: $keyword");

            for ($i = 0; $i < $maxPages; $i++) {
                $start = $i * 40;

                $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                    'q' => $keyword,
                    'orderBy' => 'relevance',
                    'printType' => 'books',
                    'langRestrict' => 'fr',
                    'maxResults' => 40,
                    'startIndex' => $start,
                    'key' => config('services.google_books.key'),
                ]);

                $books = $response['items'] ?? [];

                foreach ($books as $item) {
                    $info = $item['volumeInfo'] ?? [];

                    // Filtrage : si titre, auteurs ou langue manquants OU langue ≠ fr, on ignore
                    if (!isset($info['title'], $info['authors'], $info['language']) || $info['language'] !== 'fr') {
                        continue;
                    }

                    Book::firstOrCreate(
                        ['google_id' => $item['id']],
                        [
                            'title' => $info['title'],
                            'author' => $info['authors'][0] ?? null,
                            'description' => $info['description'] ?? null,
                            'cover_url' => $info['imageLinks']['thumbnail'] ?? null,
                            'genre' => $keyword,
                            'published_date' => $info['publishedDate'] ?? null,
                        ]
                    );
                }

                $this->info("✅ Page $i de '$keyword' importée (" . count($books) . " livres)");
                sleep(1); // éviter le blocage de l'API
            }
        }

        $this->info("🎉 Synchronisation terminée !");
    }
}
