<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Doctors extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $doctorData = [
            [
                'firstName' => 'Dr. Emily',
                'lastName' => 'Mitchell',
                'specialization' => 'Orthodontist',
            ],
            [
                'firstName' => 'Dr. Benjamin',
                'lastName' => 'Carter',
                'specialization' => 'Endodontist',
            ],
        ];

        foreach ($doctorData as $data) {
            $doctor = new \App\Entity\Doctors();
            $doctor->setFirstName($data['firstName']);
            $doctor->setLastName($data['lastName']);
            $doctor->setSpecialization($data['specialization']);
            $manager->persist($doctor);
        }

        $manager->flush();
    }
}
