<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class GetAvailableTimeSlotsDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\DateTime]
        public readonly string $dateTime,

        #[Assert\GreaterThanOrEqual(1)]
        #[Assert\Type('int')]
        public readonly int $doctorId
    ) {
    }
}