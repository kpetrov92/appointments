<?php

namespace App\Controller;

use App\Dto\GetAvailableTimeSlotsDto;
use App\Repository\DoctorsRepository;
use App\Service\Doctor\AvailableSlotsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class DoctorsController extends AbstractController
{
    #[Route('/doctor/available-slots', name: 'available-slots', methods: ['GET'])]
    public function create(#[MapQueryString] GetAvailableTimeSlotsDto $getAvailableTimeSlotsDto, AvailableSlotsService $availableSlotsService): JsonResponse
    {
        try {
            $slots = $availableSlotsService->getAvailableSlots($getAvailableTimeSlotsDto->doctorId, $getAvailableTimeSlotsDto->dateTime);
            return $this->json($slots);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/doctors', name: 'list_doctors', methods: ['GET'])]
    public function list(DoctorsRepository $doctorsRepository): JsonResponse
    {
        $doctors = $doctorsRepository->findAll();
        return $this->json($doctors, 200, [], ['groups' => 'doctor_list']);
    }
}
