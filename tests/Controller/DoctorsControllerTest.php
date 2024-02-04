<?php

namespace App\Tests\Controller;

use App\Repository\DoctorsRepository;
use App\Service\Doctor\AvailableSlotsService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DoctorsControllerTest extends WebTestCase
{
    private $client;
    private $container;

    /**
     * Set up common test components.
     */
    protected function setUp(): void
    {
        // Boot the kernel and create a client
        $this->client = static::createClient();
        // Get the container for service replacement
        $this->container = static::getContainer();
    }

    /**
     * Test getting available slots successfully.
     */
    public function testGetAvailableSlotsSuccess()
    {
        // Service mock
        $availableSlotsServiceMock = $this->createMock(AvailableSlotsService::class);
        // Assume the service returns an array of slots
        $expectedSlots = ['slot1', 'slot2', 'slot3'];
        $availableSlotsServiceMock->expects($this->once())
            ->method('getAvailableSlots')
            ->willReturn($expectedSlots);

        // Replace the real service with mock
        $this->container->set(AvailableSlotsService::class, $availableSlotsServiceMock);

        // Simulate a GET request to the route that triggers controller method
        $this->client->request('GET', '/doctor/available-slots', ["dateTime" => "2024-02-05", "doctorId" => 1], [], ['CONTENT_TYPE' => 'application/json']);

        // Assert the response status code and content type
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        // Assert the response content
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals($expectedSlots, $responseContent);
    }

    /**
     * Test listing doctors successfully.
     */
    public function testListDoctors()
    {
        // Prepare repository mock
        $doctorsRepositoryMock = $this->createMock(DoctorsRepository::class);
        // Assume the repository returns an array of doctors
        $expectedDoctors = ['doctor1', 'doctor2'];
        $doctorsRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedDoctors);

        // Replace the real repository with mock
        $this->container->set(DoctorsRepository::class, $doctorsRepositoryMock);

        // Simulate a GET request to the route that triggers controller method
        $this->client->request('GET', '/doctors');

        // Assert the response status code and content type
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        // Assert the response content
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals($expectedDoctors, $responseContent);
    }

    /**
     * Test that listing doctors will throw an exception.
     */
    public function testListDoctorsWillThrowException()
    {
        // Prepare repository mock
        $doctorsRepositoryMock = $this->createMock(DoctorsRepository::class);
        // Assume the repository throws an exception
        $doctorsRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willThrowException(new \Exception("Some error message"));

        // Replace the real repository with mock
        $this->container->set(DoctorsRepository::class, $doctorsRepositoryMock);

        // Simulate a GET request to the route that triggers controller method
        $this->client->request('GET', '/doctors');

        // Assert the response status code and content type
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        // Assert the response content
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals('error', $responseContent['status']);
        $this->assertEquals('Some error message', $responseContent['message']);
    }
}
