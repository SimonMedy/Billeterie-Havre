<?php

namespace App\Tests;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Please sign in');
    }

    public function testVisitingWhileLoggedIn(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->filter('button[type="submit"]');
        $form = $buttonCrawlerNode->form();
        $form['_username'] = 'd@d.d';
        $form['_password'] = 'dddddd';
        $client->submit($form);
        $client->followRedirect();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('a', 'You are logged in as d@d.d');
    }




}


