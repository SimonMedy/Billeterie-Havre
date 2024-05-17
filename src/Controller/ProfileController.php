<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;



#[IsGranted("ROLE_USER")]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/profile/withdraw/{id}', name: 'profile_withdraw', methods: ['POST'])]
    public function withdrawFromEvent(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
        }

        if (!$event->getReservation()->contains($user)) {
            return new JsonResponse(['error' => 'Not registered for this event'], Response::HTTP_BAD_REQUEST);
        }

        $event->removeReservation($user);
        $entityManager->persist($event);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Successfully withdrawn from the event']);
    }
}
