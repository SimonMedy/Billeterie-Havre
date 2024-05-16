<?php
namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{

    #[Route('/CalendarEvents', name: 'calendar_events', methods: ['GET'])]
    public function events(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();
        $formattedEvents = array_map(function ($event) {
            return [
                'title' => $event->getNom(),
                'start' => $event->getDate()->format('Y-m-d'),
                'description' => $event->getDescription(),    
                'extendedProps' => [                         
                    'ageRestrict' => $event->getAgeRestrict(),
                    'annule' => $event->isAnnule(),
                    'message' => $event->getMessage(),
                ]
            ];
        }, $events);
    
        return new JsonResponse($formattedEvents);
    }
    #[Route('/calendar', name: 'calendar_view')]
public function calendarView(): Response {
    return $this->render('calendar/index.html.twig');
}

    
}
