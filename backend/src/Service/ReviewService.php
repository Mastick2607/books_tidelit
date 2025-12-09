<?php

namespace App\Service;

use App\Dto\CreateReviewRequest;
use App\Entity\Book;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;

class ReviewService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createReview(CreateReviewRequest $dto): Review
    {
        $book = $this->em->getRepository(Book::class)->find($dto->bookId);
        
        if (!$book) {
            throw new \InvalidArgumentException('El libro con ID ' . $dto->bookId . ' no existe.');
        }

        $review = new Review();
        $review->setBook($book); 
        $review->setRating($dto->rating);
        $review->setComment($dto->comment);

        $this->em->persist($review);
        $this->em->flush();

        return $review;
    }
}