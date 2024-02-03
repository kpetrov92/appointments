<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentsController extends AbstractController
{
    #[Route('/appointments', name: 'app_appointments')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AppointmentsController.php',
        ]);
    }

    #[Route('/appointments/create', name: 'create_appointment', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->json(['message']);
    }

    #[Route('/appointments/doctor', name: 'doctor', methods: ['GET'])]
    public function checkSlots(Request $request): JsonResponse
    {
        $doctorId = $request->query->get('id');
        $date = $request->query->get('date');

        return $this->json(['message']);
    }
}
