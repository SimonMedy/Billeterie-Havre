<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserApiTest extends WebTestCase
{
    // Fonction pour créer un utilisateur de test
    private function createTestUser($client): array
    {
        // Générer un e-mail aléatoire
        $email = 'test_' . uniqid() . '@example.com';
        $client->request('POST', '/api/utilisateurs', [], [], ['CONTENT_TYPE' => 'application/ld+json'], json_encode([
            'email' => $email,
            'password' => 'password',
            'age' => 30
        ]));

        // Vérifie la création réussie
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        // Retourne les données de l'utilisateur créé
        $responseData = json_decode($client->getResponse()->getContent(), true);
        return $responseData;
    }

    public function testGetUsers(): void
    {
        $client = static::createClient(); //Client http

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

        // Crée un utilisateur de test et récupère son ID
        $user = $this->createTestUser($client);
        $userId = $user['id'];

        // Requête /api/utilisateurs/{id}
        $client->request('GET', '/api/utilisateurs/' . $userId);

        // Vérifie la connexion
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vérifie si la réponse contient l'utilisateur
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
    }

    public function testUserOperations(): void
    {
        $client = static::createClient();
        // Crée un utilisateur récupère son ID
        $user = $this->createTestUser($client);
        $userId = $user['id'];

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        // Test update l'utilisateur
        $client->request('PUT', '/api/utilisateurs/' . $userId, [], [], ['CONTENT_TYPE' => 'application/ld+json'], json_encode([
            'email' => 'updated@example.com',
            'password' => 'password',
            'age' => 35
        ]));

        // Vérifie que l'update a été effectué
        $client->request('GET', '/api/utilisateurs/' . $userId);
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('updated@example.com', $responseData['email']);

        // Test de suppression de l'utilisateur
        $client->request('DELETE', '/api/utilisateurs/' . $userId);
        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        // Vérifie que l'utilisateur a été supprimé
        $client->request('GET', '/api/utilisateurs/' . $userId);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
