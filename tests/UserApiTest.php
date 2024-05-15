<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserApiTest extends WebTestCase
{
    public function testGetUsers(): void
    {
        $client = static::createClient();

        // Effectue une requête GET à l'endpoint /api/utilisateurs
        $client->request('GET', '/api/utilisateurs');

        // Vérifie si la réponse est réussie (code HTTP 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vérifie si le contenu de la réponse est au format JSON
        $this->assertJson($client->getResponse()->getContent());

        // Vérifie si la réponse contient un tableau d'utilisateurs
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData['hydra:member']);
    }

    public function testCreateUser(): void
    {
        $client = static::createClient();

        // Effectue une requête POST à l'endpoint /api/utilisateurs pour créer un nouvel utilisateur
        
        $client->request('POST', '/api/utilisateurs', [], [], ['CONTENT_TYPE' => 'application/ld+json'], '{"email": "test@example.com", "password": "password", "age": 30}');
        // Vérifie si la réponse est réussie (code HTTP 201 pour une création réussie)
        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        // Vous pouvez ajouter d'autres vérifications ici en fonction de votre structure de réponse
    }

    // Ajoutez d'autres tests pour d'autres fonctionnalités comme la récupération d'un utilisateur par ID, la mise à jour d'un utilisateur, etc.
}