<?php

namespace App\Tests\Entity;

use App\Entity\Doctor;
use App\Entity\Appointment;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\ArrayCollection;

class DoctorEntityTest extends TestCase
{
    private $doctor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->doctor = new Doctor();
    }

    public function testGetSetFirstName()
    {
        $firstName = "John";
        $this->doctor->setFirstName($firstName);
        $this->assertEquals($firstName, $this->doctor->getFirstName());
    }

    public function testGetSetLastName()
    {
        $lastName = "Doe";
        $this->doctor->setLastName($lastName);
        $this->assertEquals($lastName, $this->doctor->getLastName());
    }

    public function testGetSetSpecialization()
    {
        $specialization = "Cardiology";
        $this->doctor->setSpecialization($specialization);
        $this->assertEquals($specialization, $this->doctor->getSpecialization());
    }

    public function testAppointmentsInitialization()
    {
        $this->assertInstanceOf(ArrayCollection::class, $this->doctor->getAppointments());
        $this->assertCount(0, $this->doctor->getAppointments());
    }

    public function testAddAppointment()
    {
        $appointment = $this->createMock(Appointment::class);
        $this->doctor->addAppointment($appointment);

        $this->assertCount(1, $this->doctor->getAppointments());
        $this->assertTrue($this->doctor->getAppointments()->contains($appointment));
    }

    public function testRemoveAppointment()
    {
        $appointment = $this->createMock(Appointment::class);
        $this->doctor->addAppointment($appointment);
        $this->doctor->removeAppointment($appointment);

        $this->assertCount(0, $this->doctor->getAppointments());
        $this->assertFalse($this->doctor->getAppointments()->contains($appointment));
    }
}
