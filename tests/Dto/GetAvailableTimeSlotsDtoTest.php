<?php

namespace App\Tests\Dto;

use App\Dto\GetAvailableTimeSlotsDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetAvailableTimeSlotsDtoTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * @dataProvider valueProvider
     */
    public function testConstraints($property, $value, $expectedMessage): void
    {
        $dto = new GetAvailableTimeSlotsDto(
            $property === 'dateTime' ? $value : '2024-02-04',
            $property === 'doctorId' ? $value : 1
        );

        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
    }

    public function valueProvider(): array
    {
        return [
            ['dateTime', '', 'This value should not be blank.'],
            ['dateTime', 'invalid-date', 'This value is not a valid date.'],
            ['doctorId', 0, 'This value should be greater than or equal to 1.'],
        ];
    }
}
