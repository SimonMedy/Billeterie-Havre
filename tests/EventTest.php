<?php

namespace App\Tests\Entity;

use App\Entity\Event;
use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;
use App\Entity\Type;
class EventTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $event = new Event();

        $event->setNom('Concert');
        $this->assertSame('Concert', $event->getNom());

        $date = new \DateTimeImmutable('2024-05-15');
        $event->setDate($date);
        $this->assertSame($date, $event->getDate());

        $event->setDescription('Description du concert');
        $this->assertSame('Description du concert', $event->getDescription());

        $event->setAgeRestrict(18);
        $this->assertSame(18, $event->getAgeRestrict());

        $event->setAnnule(true);
        $this->assertTrue($event->isAnnule());

        $event->setMessage('Message important');
        $this->assertSame('Message important', $event->getMessage());

        $type = new Type();
        $event->setIdType($type);
        $this->assertSame($type, $event->getIdType());
    }

    public function testReservation(): void
    {
        $event = new Event();
        $user1 = new Utilisateur();
        $user2 = new Utilisateur();

        $this->assertCount(0, $event->getReservation());

        $event->addReservation($user1);
        $this->assertCount(1, $event->getReservation());
        $this->assertTrue($event->getReservation()->contains($user1));

        $event->addReservation($user2);
        $this->assertCount(2, $event->getReservation());
        $this->assertTrue($event->getReservation()->contains($user2));

        $event->removeReservation($user1);
        $this->assertCount(1, $event->getReservation());
        $this->assertFalse($event->getReservation()->contains($user1));
    }
}
