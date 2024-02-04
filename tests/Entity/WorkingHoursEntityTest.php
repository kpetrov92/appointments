<?php

namespace App\Tests\Entity;

use App\Entity\WorkingHours;
use PHPUnit\Framework\TestCase;

class WorkingHoursTest extends TestCase
{
    private $workingHours;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workingHours = new WorkingHours();
    }

    public function testGetSetDayOfWeek()
    {
        $dayOfWeek = 1; // Monday
        $this->workingHours->setDayOfWeek($dayOfWeek);
        $this->assertEquals($dayOfWeek, $this->workingHours->getDayOfWeek());
    }

    public function testGetSetStartTime()
    {
        $startTime = '08:00';
        $this->workingHours->setStartTime($startTime);
        $this->assertEquals($startTime, $this->workingHours->getStartTime());
    }

    public function testGetSetEndTime()
    {
        $endTime = '17:00';
        $this->workingHours->setEndTime($endTime);
        $this->assertEquals($endTime, $this->workingHours->getEndTime());
    }
}
