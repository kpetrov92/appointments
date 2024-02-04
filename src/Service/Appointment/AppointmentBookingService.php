<?php

namespace App\Service\Appointment;

use App\Dto\CreateAppointmentDto;
use App\Entity\Appointment;
use App\Repository\DoctorsRepository;
use App\Service\Doctor\AvailableSlotsService;
use Doctrine\ORM\EntityManagerInterface;

class AppointmentBookingService
{
    private AvailableSlotsService $availableSlotsService;
    private DoctorsRepository $doctorsRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        AvailableSlotsService $availableSlotsService,
        DoctorsRepository $doctorsRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->availableSlotsService = $availableSlotsService;
        $this->doctorsRepository = $doctorsRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param CreateAppointmentDto $appointmentDto
     * @return bool
     * @throws \Exception
     */
    public function bookAppointment(CreateAppointmentDto $appointmentDto): bool
    {
        $date = (new \DateTime($appointmentDto->dateTime))->format('Y-m-d');
        // Get available slots for the given doctor and date
        $availableSlots = $this->availableSlotsService->getAvailableSlots($appointmentDto->doctorId, $date);

        // Convert the requested dateTime to a slot format
        $requestedSlotTime = (new \DateTime($appointmentDto->dateTime))->format('H:i');

        $doctor = $this->doctorsRepository->findOneBy(["id" => $appointmentDto->doctorId]);

        if (!$doctor) {
            throw new \Exception("Doctor with ID {$appointmentDto->doctorId} not found.");
        }

        $isSlotAvailable = false;
        foreach ($availableSlots as $slot) {
            if ($slot['time'] === $requestedSlotTime && $slot['status'] === 0) {
                $this->bookSlot($appointmentDto, $doctor);
                $isSlotAvailable = true;
                break;
            }
        }

        if (!$isSlotAvailable) {
            throw new \Exception("Requested slot is not available or not free.");
        }

        return true;
    }

    /**
     * @param $appointmentDto
     * @param $doctor
     * @return void
     * @throws \Exception
     */
    private function bookSlot($appointmentDto, $doctor): void
    {
        try {
            // Slot is available and free, proceed to book the appointment
            $appointment = new Appointment();
            $appointment->setPatientFirstName($appointmentDto->patientFirstName);
            $appointment->setPatientLastName($appointmentDto->patientLastName);
            $appointment->setEmail($appointmentDto->email);
            $appointment->setPhoneNumber($appointmentDto->phoneNumber);
            $appointment->setDateTime(new \DateTime($appointmentDto->dateTime));
            $appointment->setDoctor($doctor);

            $this->entityManager->persist($appointment);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Something went wrong!");
        }
    }
}
