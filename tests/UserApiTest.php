<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserApiTest extends WebTestCase
{
    public function testGetUsers(): void
    {
        $client = static::createClient();

        // Requete /api/utilisateurs
        $client->request('GET', '/api/utilisateurs');

        // Vérifie la connection
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vérifie si le contenu de la réponse est au format JSON
        $this->assertJson($client->getResponse()->getContent());

        // Vérifie si la réponse contient les utilisateurs
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData['hydra:member']);
    }

    public function testGetUserById(): void
    {
        $client = static::createClient();
        // Requete /api/utilisateurs
        $client->request('GET', '/api/utilisateurs/1');

        // Vérifie la connection
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vérifie si la réponse contient l'utilisateur
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    public function testUserOperations(): void
    {
        // Test POST un utilisateur
        $client = static::createClient();
        // Générer un e-mail aléatoire
        $email = 'test_' . uniqid() . '@example.com';
        $client->request('POST', '/api/utilisateurs', [], [], ['CONTENT_TYPE' => 'application/ld+json'], json_encode([
            'email' => $email,
            'password' => 'password',
            'age' => 30
        ]));
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        // Test update un utilisateur
        $client->request('PUT', '/api/utilisateurs/1', [], [], ['CONTENT_TYPE' => 'application/ld+json'], '{"email": "updated@example.com", "password": "password", "age": 35}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('PUT', '/api/utilisateurs/1', [], [], ['CONTENT_TYPE' => 'application/ld+json'], '{"email": "notupdated@example.com", "password": "password", "age": 30}');
    }
    
}