<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WorkingHours extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $workingHours = [
            1 => ['08:00:00', '15:00:00'], // Monday
            2 => ['08:00:00', '15:00:00'], // Tuesday
            3 => ['08:00:00', '15:00:00'], // Wednesday
            4 => ['08:00:00', '15:00:00'], // Thursday
            5 => ['08:00:00', '15:00:00'], // Friday
        ];

        foreach ($workingHours as $dayOfWeek => $times) {
            $wh = new \App\Entity\WorkingHours();
            $wh->setDayOfWeek($dayOfWeek);
            $wh->setStartTime($times[0]);
            $wh->setEndTime($times[1]);
            $manager->persist($wh);
        }

        $manager->flush();
    }
}
