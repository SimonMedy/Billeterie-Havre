<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
    
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
    

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }


    #[Route('/{id}/annulerevent', name: 'annulerevent', methods: ['GET','POST'])]
    public function annulerevent(Event $event,EntityManagerInterface $entityManager): Response
    {
        $event->setAnnule(true);
        $entityManager->persist($event);
        $entityManager->flush();

        return new JsonResponse($event);

    }



    #[Route('/{id}/activerevent', name: 'activerevent', methods: ['GET','POST'])]
    public function activerevent(Event $event,EntityManagerInterface $entityManager): Response
    {
        $event->setAnnule(false);
        $entityManager->persist($event);
        $entityManager->flush();

        return new JsonResponse($event);

    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}/participate', name: 'app_event_participate', methods: ['POST'])]
    public function participate(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 
        if (!$user) {
            $this->addFlash('error', 'You must be logged in to participate.'); 
            return $this->redirectToRoute('app_login');
        }
    
        if ($event->getReservation()->contains($user)) {
            $this->addFlash('error', 'You are already registered for this event.');
            return $this->redirectToRoute('app_event_index');
        }
    
        $event->addReservation($user);
        $entityManager->persist($event);
        $entityManager->flush();
    
        $this->addFlash('success', 'You have successfully registered for the event.');
        return $this->redirectToRoute('app_event_index');
    }
    

}