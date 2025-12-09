<?php

namespace App\Controller;

use App\Dto\CreateReviewRequest;
use App\Service\ReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class ReviewController extends AbstractController
{
    #[Route('/reviews', name: 'create_review', methods: ['POST'])]
    public function create(
        Request $request, 
        SerializerInterface $serializer, 
        ValidatorInterface $validator, 
        ReviewService $reviewService
    ): JsonResponse {
        try {
            $dto = $serializer->deserialize($request->getContent(), CreateReviewRequest::class, 'json');

            $errors = $validator->validate($dto);
            if (count($errors) > 0) {
                return $this->json($errors, 400); 
            }

            $review = $reviewService->createReview($dto);

            return $this->json([
                'id' => $review->getId(),
                'created_at' => $review->getCreatedAt()->format('Y-m-d H:i:s'),
                'message' => 'Reseña creada correctamente.'
            ], 201);

        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400); // 400 si el libro no existe 
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Error interno del servidor',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}