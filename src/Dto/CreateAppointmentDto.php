<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAppointmentDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $patientFirstName,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $patientLastName,

        #[Assert\NotBlank]
        #[Assert\Email]
        public readonly string $email,

        #[Assert\NotBlank]
        #[Assert\Regex(pattern: "/^\+?[1-9]\d{1,14}$/",)]
        public readonly string $phoneNumber,

        #[Assert\NotBlank]
        #[Assert\DateTime]
        public readonly string $dateTime,

        #[Assert\GreaterThanOrEqual(1)]
        #[Assert\Type('int')]
        public readonly int $doctorId
    ) {
    }
}