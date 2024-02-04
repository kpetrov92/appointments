<?php

namespace App\Controller;

use App\Dto\CreateAppointmentDto;
use App\Service\Appointment\AppointmentBookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentsController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/appointments/create', name: 'create_appointment', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateAppointmentDto $createAppointmentDto, AppointmentBookingService $appointmentBookingService): JsonResponse
    {
        try {
            $appointmentBookingService->bookAppointment($createAppointmentDto);
            return $this->json([
                'status' => 'success',
                'message' => 'Appointment booked successfully'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
