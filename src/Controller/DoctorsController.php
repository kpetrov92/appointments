<?php

namespace App\Controller;

use App\Dto\GetAvailableTimeSlotsDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class DoctorsController extends AbstractController
{
    #[Route('/doctor/available-slots', name: 'available-slots', methods: ['GET'])]
    public function create(#[MapQueryString] GetAvailableTimeSlotsDto $getAvailableTimeSlotsDto): JsonResponse
    {
        return $this->json($getAvailableTimeSlotsDto);
    }
}
