<?php

namespace App\Service\Doctor;

use App\Entity\WorkingHours;
use App\Repository\AppointmentsRepository;
use App\Repository\WorkingHoursRepository;

class AvailableSlotsService
{
    private WorkingHoursRepository $workingHoursRepository;
    private AppointmentsRepository $appointmentsRepository;

    public function __construct(WorkingHoursRepository $workingHoursRepository, AppointmentsRepository $appointmentsRepository)
    {
        $this->workingHoursRepository = $workingHoursRepository;
        $this->appointmentsRepository = $appointmentsRepository;
    }

    /**
     * @param int $doctorId
     * @param string $dateTime
     * @return array
     * @throws \Exception
     */
    public function getAvailableSlots(int $doctorId, string $dateTime): array
    {
        $dataTimeObj = new \DateTime($dateTime);
        $dateOnlyObj = new \DateTime($dataTimeObj->format('Y-m-d'));
        // Convert the date to a day of the week (1 for Monday, 7 for Sunday)
        $dayOfWeek = (int) $dataTimeObj->format('N');

        // 1. Get working hours for that day
        $workingHours = $this->workingHoursRepository->findOneBy(['dayOfWeek' => $dayOfWeek]);

        // If there are no working hours for that day, return an empty array
        if (!$workingHours) {
            return [];
        }

        // 2. Get all appointments for the doctor for that day
        $appointments = $this->appointmentsRepository->findByDoctorAndDate($doctorId, $dateOnlyObj);

        // Compute available slots based on working hours and appointments
        return $this->computeAvailableSlots($workingHours, $appointments);
    }

    private function computeAvailableSlots(WorkingHours $workingHours, array $appointments): array
    {
        $slots = [];
        $slotDuration = '+1 hour'; // each slot is 1 hour long

        // Convert working hours start and end times to DateTime objects
        $startTime = \DateTime::createFromFormat('H:i:s', $workingHours->getStartTime());
        $endTime = \DateTime::createFromFormat('H:i:s', $workingHours->getEndTime());

        // Generate time slots
        while ($startTime < $endTime) {
            $slot = [
                'time' => $startTime->format('H:i'),
                'status' => 0 // assume the slot is free initially
            ];

            // Check each appointment to see if it overlaps with the current slot
            foreach ($appointments as $appointment) {
                // Get the start time of the appointment
                $appointmentStartTime = $appointment->getDateTime();

                // If the appointment start time is the same as the slot time, mark the slot as booked
                if ($appointmentStartTime->format('H:i') == $slot['time']) {
                    $slot['status'] = 1; // mark as booked
                    break;
                }
            }

            // Add the slot to the array of slots
            $slots[] = $slot;

            // Move to the next slot
            $startTime->modify($slotDuration);
        }

        return $slots;
    }
}