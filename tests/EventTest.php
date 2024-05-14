<?php 


// tests/Entity/EventTest.php

namespace App\Tests\Entity;

use App\Entity\Event;
use PHPUnit\Framework\TestCase;
use App\Entity\User;


class EventTest extends TestCase
{
    public function testEventAttributes()
    {
        $event = new Event();

        $event->setNom('Concert')
              ->setDate(new \DateTimeImmutable('2023-05-14'))
              ->setDescription('Concert de musique classique')
              ->setAgeRestrict(18)
              ->setAnnule(false)
              ->setMessage('Ne manquez pas cet événement exceptionnel');

        $this->assertEquals('Concert', $event->getNom());
        $this->assertEquals('2023-05-14', $event->getDate()->format('Y-m-d'));
        $this->assertEquals('Concert de musique classique', $event->getDescription());
        $this->assertEquals(18, $event->getAgeRestrict());
        $this->assertFalse($event->isAnnule());
        $this->assertEquals('Ne manquez pas cet événement exceptionnel', $event->getMessage());
    }

    public function testAddRemoveReservation()
    {
        $event = new Event();
        $user = $this->createMock(User::class);

        $event->addReservation($user);
        $this->assertCount(1, $event->getReservation());

        $event->removeReservation($user);
        $this->assertCount(0, $event->getReservation());
    }
}



?>