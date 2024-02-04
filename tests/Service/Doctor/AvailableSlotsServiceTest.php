<?php

namespace App\Tests\Service\Doctor;

use App\Entity\Appointment;
use App\Entity\WorkingHours;
use App\Repository\AppointmentsRepository;
use App\Repository\WorkingHoursRepository;
use App\Service\Doctor\AvailableSlotsService;
use PHPUnit\Framework\TestCase;

class AvailableSlotsServiceTest extends TestCase
{
    private $workingHoursRepositoryMock;
    private $appointmentsRepositoryMock;
    private $service;
    private $workingHoursMock;

    protected function setUp(): void
    {
        $this->workingHoursMock = $this->createMock(WorkingHours::class);
        $this->workingHoursMock->method('getStartTime')->willReturn('08:00:00');
        $this->workingHoursMock->method('getEndTime')->willReturn('15:00:00');

        $this->workingHoursRepositoryMock = $this->createMock(WorkingHoursRepository::class);
        $this->workingHoursRepositoryMock->method('findOneBy')->willReturn($this->workingHoursMock);

        $this->appointmentsRepositoryMock = $this->createMock(AppointmentsRepository::class);

        $this->service = new AvailableSlotsService($this->workingHoursRepositoryMock, $this->appointmentsRepositoryMock);
    }

    /**
     * @dataProvider slotsProvider
     * @throws \Exception
     */
    public function testGetAvailableSlots($doctorId, $dateTime, $appointmentsData, $expectedSlots)
    {
        // Arrange
        $this->appointmentsRepositoryMock->method('findByDoctorAndDate')->willReturn($this->createAppointmentsMocks($appointmentsData));

        // Act
        $slots = $this->service->getAvailableSlots($doctorId, $dateTime);

        // Assert
        $this->assertEquals($expectedSlots, $slots);
    }

    public function slotsProvider(): array
    {
        $doctorId = 1;
        $dateTime = '2023-02-01 08:00:00';
        return [
            // No Booked Appointments
            [
                $doctorId,
                $dateTime,
                [],
                [
                    ["time" => "08:00", "status" => 0],
                    ["time" => "09:00", "status" => 0],
                    ["time" => "10:00", "status" => 0],
                    ["time" => "11:00", "status" => 0],
                    ["time" => "12:00", "status" => 0],
                    ["time" => "13:00", "status" => 0],
                    ["time" => "14:00", "status" => 0],
                ],
            ],
            // Some Booked Appointments
            [
                $doctorId,
                $dateTime,
                [
                    '08:00:00',
                    '09:00:00',
                ],
                [
                    ["time" => "08:00", "status" => 1],
                    ["time" => "09:00", "status" => 1],
                    ["time" => "10:00", "status" => 0],
                    ["time" => "11:00", "status" => 0],
                    ["time" => "12:00", "status" => 0],
                    ["time" => "13:00", "status" => 0],
                    ["time" => "14:00", "status" => 0],
                ],
            ],
            // All Booked Appointments
            [
                $doctorId,
                $dateTime,
                [
                    '08:00:00',
                    '09:00:00',
                    '10:00:00',
                    '11:00:00',
                    '12:00:00',
                    '13:00:00',
                    '14:00:00',
                ],
                [
                    ["time" => "08:00", "status" => 1],
                    ["time" => "09:00", "status" => 1],
                    ["time" => "10:00", "status" => 1],
                    ["time" => "11:00", "status" => 1],
                    ["time" => "12:00", "status" => 1],
                    ["time" => "13:00", "status" => 1],
                    ["time" => "14:00", "status" => 1],
                ],
            ],
        ];
    }

    private function createAppointmentsMocks($appointmentTimes): array
    {
        return array_map(function ($appointmentTime) {
            $appointmentMock = $this->createMock(Appointment::class);
            $appointmentMock->method('getDateTime')->willReturn(new \DateTime('2023-02-01 ' . $appointmentTime));
            return $appointmentMock;
        }, $appointmentTimes);
    }
}
