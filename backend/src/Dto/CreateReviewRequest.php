<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateReviewRequest
{
    
public function __construct(
        #[Assert\NotBlank(message: "El rating es obligatorio.")]
        #[Assert\Range(min: 1, max: 5)]
        public readonly int $rating,

        #[Assert\NotBlank(message: "El comentario no puede estar vacío.")]
        #[Assert\Length(min: 10, max: 500)]
        public readonly string $comment,

        // Capturamos el ID primitivo que envía el cliente
        #[Assert\NotNull(message: "El ID del libro es obligatorio.")]
        #[Assert\Type(type: 'integer')]
        #[SerializedName("book_id")]
        public readonly mixed $bookId 
    ) {
    }


}