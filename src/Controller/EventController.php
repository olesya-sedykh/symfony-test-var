<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Event;
use App\Form\AddEventType;

use Doctrine\ORM\EntityManagerInterface;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    // #[Route('/add_event', name: 'add_event')]
    // public function addEvent(EntityManagerInterface $entityManager): Response
    // {
    //     // Создаем новый экземпляр формы для очистки полей
    //     $event = new Event();
    //     $form = $this->createForm(AddEventType::class, $event);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $imageFile = $form->get('image')->getData();
    //         if ($imageFile) {
    //             $imageMimeType = $imageFile->getMimeType();
    //             if (!in_array($imageMimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
    //                 $this->addFlash('error', 'Only JPEG, PNG, and GIF files are allowed.');
    //                 return $this->redirectToRoute('add_event');
    //             }

    //             $imageFileName = md5(uniqid()).'.'.$imageFile->guessExtension();
    //             $imageFile->move(
    //                 $this->getParameter('images_directory'),
    //                 $imageFileName
    //             );
    //             $event->setImage('img/'.$imageFileName);
    //         }
    //         $entityManager->persist($event);
    //         $entityManager->flush();
    //         $this->addFlash('success', 'Спасибо за ваш отзыв, он будет опубликован после проверки.');
    //     }
    
    //     return $this->render('event/add_event.html.twig', [
    //         'eventForm' => $form->createView(),
    //     ]);
    // }

    #[Route('/api/events', name: 'events_list')]
    public function getEvents(EventRepository $eventRepository): JsonResponse
    {
        $events = $eventRepository->findAll();
        
        // Сериализация данных в JSON
        $data = [];
        foreach ($events as $event) {
            $data[] = [
                'id' => $event->getId(),
                'name' => $event->getName(),
                'content' => $event->getContent(),
                'image' => $event->getImage(),
                'date' => $event->getDate(),
                'category' => $event->getCategory(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/api/events/{id}', name: 'event_detail')]
    public function getEventDetail($id, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }

        $data = [
            'id' => $event->getId(),
            'name' => $event->getName(),
            'content' => $event->getContent(),
            'image' => $event->getImage(),
            'date' => $event->getDate(),
            'category' => $event->getCategory(),
        ];

        return new JsonResponse($data);
    }
}
