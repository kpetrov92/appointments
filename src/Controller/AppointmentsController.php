<?php

namespace App\Controller;

use App\Dto\CreateAppointmentDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentsController extends AbstractController
{
    #[Route('/appointments/create', name: 'create_appointment', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateAppointmentDto $createAppointmentDto): JsonResponse
    {
        return $this->json($createAppointmentDto);
    }
}
