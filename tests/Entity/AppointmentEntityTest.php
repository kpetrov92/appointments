<?php

namespace App\Tests\Entity;

use App\Entity\Appointment;
use App\Entity\Doctor;
use PHPUnit\Framework\TestCase;

class AppointmentEntityTest extends TestCase
{
    private $appointment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->appointment = new Appointment();
    }

    public function testGetSetDateTime()
    {
        $dateTime = new \DateTime();
        $this->appointment->setDateTime($dateTime);
        $this->assertEquals($dateTime, $this->appointment->getDateTime());
    }

    public function testGetSetPatientFirstName()
    {
        $firstName = "John";
        $this->appointment->setPatientFirstName($firstName);
        $this->assertEquals($firstName, $this->appointment->getPatientFirstName());
    }

    public function testGetSetPatientLastName()
    {
        $lastName = "Doe";
        $this->appointment->setPatientLastName($lastName);
        $this->assertEquals($lastName, $this->appointment->getPatientLastName());
    }

    public function testGetSetEmail()
    {
        $email = "john.doe@example.com";
        $this->appointment->setEmail($email);
        $this->assertEquals($email, $this->appointment->getEmail());
    }

    public function testGetSetPhoneNumber()
    {
        $phoneNumber = "1234567890";
        $this->appointment->setPhoneNumber($phoneNumber);
        $this->assertEquals($phoneNumber, $this->appointment->getPhoneNumber());
    }

    public function testGetSetDoctor()
    {
        $doctor = $this->createMock(Doctor::class);
        $this->appointment->setDoctor($doctor);
        $this->assertEquals($doctor, $this->appointment->getDoctor());
    }
}
