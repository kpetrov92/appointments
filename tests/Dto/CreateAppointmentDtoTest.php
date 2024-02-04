<?php

namespace App\Tests\Dto;

use App\Dto\CreateAppointmentDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAppointmentDtoTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * @return void
     */
    public function testValidDto(): void
    {
        $dto = new CreateAppointmentDto(
            'John',
            'Wick',
            'john.wick@example.com',
            '+12345678901',
            '2024-02-04 09:00:00',
            1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    /**
     * @dataProvider blankValueProvider
     */
    public function testBlankValues($property, $value, $expectedMessage): void
    {
        $dto = new CreateAppointmentDto(
            $property === 'patientFirstName' ? $value : 'John',
            $property === 'patientLastName' ? $value : 'Doe',
            $property === 'email' ? $value : 'john.doe@example.com',
            $property === 'phoneNumber' ? $value : '+12345678901',
            $property === 'dateTime' ? $value : '2024-02-04 09:00:00',
            $property === 'doctorId' ? $value : 1
        );

        $violations = $this->validator->validate($dto);
        $this->assertCount(1, $violations);
        $this->assertSame($expectedMessage, $violations[0]->getMessage());
    }

    public function blankValueProvider(): array
    {
        return [
            ['patientFirstName', '', 'This value should not be blank.'],
            ['patientLastName', '', 'This value should not be blank.'],
            ['email', '', 'This value should not be blank.'],
            ['phoneNumber', '', 'This value should not be blank.'],
            ['dateTime', '', 'This value should not be blank.'],
            ['doctorId', 0, 'This value should be greater than or equal to 1.'],
        ];
    }


    /**
     * @return void
     */
    public function testInvalidEmailDto(): void
    {
        $dto = new CreateAppointmentDto(
            'John',
            'Wick',
            'johnexample.com',
            '+12345678901',
            '2024-02-04 09:00:00',
            1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame("This value is not a valid email address.", $violations[0]->getMessage());
    }

    /**
     * @return void
     */
    public function testInvalidPhoneDto(): void
    {
        $dto = new CreateAppointmentDto(
            'John',
            'Wick',
            'john@example.com',
            'sad',
            '2024-02-04 09:00:00',
            1
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertSame("This value is not valid.", $violations[0]->getMessage());
    }

}
