<?php
namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'app_calendar')]
    public function index(): Response
    {
        return $this->render('calendar/index.html.twig');
    }

    #[Route('/api/events', name: 'api_events')]
    public function events(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();
        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event->getNom(),
                'start' => $event->getDate()->format('Y-m-d'),
                'description' => $event->getDescription(),
                'ageRestrict' => $event->getAgeRestrict(),
                'annule' => $event->isAnnule(),
                'message' => $event->getMessage(),
            ];
        }

        return new JsonResponse($formattedEvents);
    }
}
