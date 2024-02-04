<?php

namespace App\Tests\Controller;

use App\Dto\CreateAppointmentDto;
use App\Service\Appointment\AppointmentBookingService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppointmentsControllerTest extends WebTestCase
{
    private $client;
    private $container;
    private $createAppointmentDto;

    /**
     * Set up common test components.
     */
    protected function setUp(): void
    {
        // Boot the kernel and create a client
        $this->client = static::createClient();
        $this->container = static::getContainer();
        $this->createAppointmentDto = new CreateAppointmentDto('John', 'Doe', 'john@example.com', '+3598888888', '2024-02-04 09:00:00', 1);
    }

    /**
     * Test creating an appointment successfully.
     */
    public function testCreateAppointmentSuccess()
    {
        // Prepare service mock
        $appointmentBookingServiceMock = $this->createMock(AppointmentBookingService::class);
        $appointmentBookingServiceMock->expects($this->once())
            ->method("bookAppointment")
            ->willReturn(true);

        // Replace the real service with mock
        $this->container->set(AppointmentBookingService::class, $appointmentBookingServiceMock);

        // Request to the route that triggers controller method
        $this->client->request('POST', '/appointments/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($this->createAppointmentDto));

        // Assert the response status code
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        // Add more assertions here as needed
    }

    /**
     * Test creating an appointment failure.
     */
    public function testCreateAppointmentFailure()
    {
        // Prepare service mock
        $appointmentBookingServiceMock = $this->createMock(AppointmentBookingService::class);
        $appointmentBookingServiceMock->expects($this->once())
            ->method("bookAppointment")
            ->willThrowException(new \Exception("Some error message"));

        // Replace the real service with mock
        $this->container->set(AppointmentBookingService::class, $appointmentBookingServiceMock);

        // Request to the route that triggers controller method
        $this->client->request('POST', '/appointments/create', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($this->createAppointmentDto));

        // Assert the response status code and content
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseContent['status']);
        $this->assertEquals('Some error message', $responseContent['message']);
    }
}


