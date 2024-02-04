<?php

namespace App\Tests\Entity;

use App\Entity\BaseEntity;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use DateTime;

class BaseEntityTest extends TestCase
{
    private $baseEntity;

    protected function setUp(): void
    {
        parent::setUp();
        // Mocking BaseEntity as it's abstract and can't be instantiated.
        $this->baseEntity = $this->getMockForAbstractClass(BaseEntity::class);
    }

    public function testGetSetCreatedAt()
    {
        $createdAt = new DateTime();
        $this->baseEntity->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $this->baseEntity->getCreatedAt());
    }

    public function testGetSetUpdatedAt()
    {
        $updatedAt = new DateTime();
        $this->baseEntity->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $this->baseEntity->getUpdatedAt());
    }

    public function testSetCreatedAtValue()
    {
        // Simulate the prePersist event
        $this->baseEntity->setCreatedAtValue();
        $this->assertInstanceOf(DateTimeInterface::class, $this->baseEntity->getCreatedAt());
    }

    public function testSetUpdatedAtValue()
    {
        // Simulate the preUpdate event
        $this->baseEntity->setUpdatedAtValue();
        $this->assertInstanceOf(DateTimeInterface::class, $this->baseEntity->getUpdatedAt());
    }
}
