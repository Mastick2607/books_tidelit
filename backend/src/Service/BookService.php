<?php

namespace App\Service;

use App\Repository\BookRepository;

class BookService
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Obtiene la lista de libros procesada con su promedio de rating.
     * Cumple con la eficiencia al usar el QueryBuilder del repositorio[cite: 65, 66].
     */
    public function getBooksWithRatings(): array
    {
        $data = $this->bookRepository->findAllWithAverageRating();

        return array_map(function($item) {
            return [
                'title' => $item['title'],
                'author' => $item['author'],
                'published_year' => (int) $item['published_year'],
                'average_rating' => $item['average_rating'] ? round((float)$item['average_rating'], 1) : 0
            ];
        }, $data);
    }
}