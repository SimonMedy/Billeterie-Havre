<?php

namespace App\Tests;


use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(Utilisateur::class);

        // Remove any existing users from the test database
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        // Create a User fixture
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get('security.user_password_hasher');

        $user = (new Utilisateur())->setEmail('testphpunit@php.php');
        $user->setPassword($passwordHasher->hashPassword($user, 'phpunit'));
        $user -> setAge(25);

        $em->persist($user);
        $em->flush();
    }

    public function testLogin(): void
    {

        // Denied - Can't login with invalid email address.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'd@d.d',
            '_password' => 'testphpunit@php.php',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Denied - Can't login with invalid password.
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Sign in', [
            '_username' => 'testphpunit@php.php',
            '_password' => 'dddddd',
        ]);

        self::assertResponseRedirects('/login');

        $this->client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        $this->client->submitForm('Sign in', [
            '_username' => 'testphpunit@php.php',
            '_password' => 'phpunit',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');

    }
}

