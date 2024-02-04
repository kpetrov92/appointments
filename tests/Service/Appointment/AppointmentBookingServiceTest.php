<?php

namespace App\Tests\Service\Appointment;

use App\Dto\CreateAppointmentDto;
use App\Entity\Doctor;
use App\Repository\DoctorsRepository;
use App\Service\Appointment\AppointmentBookingService;
use App\Service\Doctor\AvailableSlotsService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class AppointmentBookingServiceTest extends TestCase
{
    private $availableSlotsService;
    private $doctorsRepository;
    private $entityManager;

    protected function setUp(): void
    {
        // Create mock objects for dependencies
        $this->availableSlotsService = $this->createMock(AvailableSlotsService::class);
        $this->doctorsRepository = $this->createMock(DoctorsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function testBookAppointmentSuccess()
    {
        // Mock available slots for a doctor
        $this->availableSlotsService->expects($this->once())
            ->method('getAvailableSlots')
            ->willReturn([['time' => '09:00', 'status' => 0]]);

        // Mock finding a doctor
        $this->doctorsRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(new Doctor());

        // Mock the entity manager
        $this->entityManager->expects($this->once())
            ->method('persist');
        $this->entityManager->expects($this->once())
            ->method('flush');

        // Create an instance of the AppointmentBookingService
        $appointmentService = new AppointmentBookingService(
            $this->availableSlotsService,
            $this->doctorsRepository,
            $this->entityManager
        );

        // Create appointment DTO
        $appointmentDto = new CreateAppointmentDto('John', 'Doe', 'john@example.com', '+3598888888', '2024-02-04 09:00:00', 1);

        // Book an appointment and assert it returns true
        $result = $appointmentService->bookAppointment($appointmentDto);
        $this->assertTrue($result);
    }

    public function testBookAppointmentDoctorNotFound()
    {
        // Mock the doctor not found scenario
        $this->doctorsRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        // Create an instance of the AppointmentBookingService
        $appointmentService = new AppointmentBookingService(
            $this->availableSlotsService,
            $this->doctorsRepository,
            $this->entityManager
        );

        // Create appointment DTO
        $appointmentDto = new CreateAppointmentDto('John', 'Doe', 'john@example.com', '+3598888888', '2024-02-04 09:00:00', 1);

        // Expecting an exception to be thrown
        $this->expectException(\Exception::class);

        // Book an appointment, which should throw an exception
        $appointmentService->bookAppointment($appointmentDto);
    }

    public function testBookAppointmentSlotNotAvailable()
    {
        // Available slots for a doctor, where the requested slot is not available
        $this->availableSlotsService->expects($this->once())
            ->method('getAvailableSlots')
            ->willReturn([['time' => '09:00', 'status' => 1], ['time' => '10:00', 'status' => 0]]);

        // Mock finding a doctor
        $this->doctorsRepository->expects($this->once())
            ->method('findOneBy')
            ->willReturn(new Doctor());

        // Create an instance of the AppointmentBookingService
        $appointmentService = new AppointmentBookingService(
            $this->availableSlotsService,
            $this->doctorsRepository,
            $this->entityManager
        );

        // Create appointment DTO
        $appointmentDto = new CreateAppointmentDto('John', 'Doe', 'john@example.com', '+3598888888', '2024-02-04 09:00:00', 1);

        // Expecting an exception to be thrown
        $this->expectException(\Exception::class);

        // Book an appointment, which should throw an exception
        $appointmentService->bookAppointment($appointmentDto);
    }

}
