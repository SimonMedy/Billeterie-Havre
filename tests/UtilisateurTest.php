<?php

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testGettersAndSetters()
    {
        $user = new Utilisateur();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());

        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());

        $user->setAge(30);
        $this->assertEquals(30, $user->getAge());

        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $user->setRoles($roles);
        $this->assertEquals($roles, $user->getRoles());
        $this->assertNull($user->getId());
    }

    public function testUserIdentifier()
    {
        $user = new Utilisateur();
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }
}
