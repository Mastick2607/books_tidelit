<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BookController extends AbstractController
{
   
    #[Route('/books', name: 'books_list', methods: ['GET'])]
    public function index(BookService $bookService): JsonResponse
    {
        try {
            $books = $bookService->getBooksWithRatings();
            return $this->json($books, 200);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'No se pudieron recuperar los libros',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}