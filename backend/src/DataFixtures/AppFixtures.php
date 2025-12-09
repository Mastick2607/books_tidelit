<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Book;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {$booksData = [
            [
                'title' => 'El Arte de Programar software', 
                'author' => 'Donald Knuth', 
                'year' => 1968
            ], 
            [
                'title' => 'Clean Code para desarrolladores', 
                'author' => 'Robert C. Martin', 
                'year' => 2008
            ], 
            [
                'title' => 'Refactoring', 
                'author' => 'Martin Fowler', 
                'year' => 1999
            ], 
        ];

        $bookEntities = [];
        foreach ($booksData as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setPublishedYear($data['year']);
            $manager->persist($book);
            $bookEntities[] = $book;
        }

        
        $reviewComments = [
            "Imprescindible para cualquier programador.",
            "Un poco denso pero vale la pena leerlo.",
            "fundamentos que cambian tu forma de trabajar.",
            "Muy buena guía y es práctica.",
            "Debería ser lectura obligatoria.",
            "Muy bien explicado y muy estructurado."
        ];

        $commentIndex = 0;
        foreach ($bookEntities as $book) {
            for ($i = 0; $i < 2; $i++) {
                $review = new Review();
                $review->setBook($book); 
                $review->setRating(rand(3, 5)); 
                $review->setComment($reviewComments[$commentIndex]);
                $manager->persist($review);
                $commentIndex++;
            }
        }

        $manager->flush();
    }
}
