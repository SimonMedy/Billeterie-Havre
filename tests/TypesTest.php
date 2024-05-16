<?php

namespace App\Tests\Entity;

use App\Entity\Type;
use App\Entity\Event;
use PHPUnit\Framework\TestCase;

class TypesTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $type = new Type();

        $type->setNom('Concert');
        $this->assertSame('Concert', $type->getNom());
    }

    public function testAddAndRemoveEvent(): void
    {
        $type = new Type();
        $event1 = new Event();
        $event2 = new Event();

        $this->assertCount(0, $type->getEvents());

        $type->addEvent($event1);
        $this->assertCount(1, $type->getEvents());
        $this->assertTrue($type->getEvents()->contains($event1));
        $this->assertSame($type, $event1->getIdType());

        $type->addEvent($event2);
        $this->assertCount(2, $type->getEvents());
        $this->assertTrue($type->getEvents()->contains($event2));
        $this->assertSame($type, $event2->getIdType());

        $type->removeEvent($event1);
        $this->assertCount(1, $type->getEvents());
        $this->assertFalse($type->getEvents()->contains($event1));
        $this->assertNull($event1->getIdType());

    }
}
