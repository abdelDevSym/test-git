<?php

namespace TodoListBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TodoListBundle\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use TodoListBundle\Form\EventType;




class DefaultController extends Controller
{


    public function indexAction()
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);

        $events = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findAll();

        // or render a template
        // in the template, print things with {{ product.name }}
         return $this->render('TodoListBundle:Default:index.html.twig', ['events' => $events ,'form' => $form->createView()]);

    }

    public function addEventAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
        }

        $reponse = new JsonResponse(array(
            'id'=>$event->getId(),
            'nom'=>$event->getNom(),
            'description'=>$event->getDescription(),
            'location'=>$event->getLocation(),
            'type'=>$event->getType()->getLibelle()
        ));
        return $reponse;

    }

    public function updateEventAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idEvent=$request->get('idEvent');

        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($idEvent);

        $form = $this->createForm(EventType::class, $event);

        return $this->render('TodoListBundle:Default:edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function update_EventAction(Request $request)
    {
        $idEvent=$request->get('idEvent');


        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($idEvent);

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
        }


        $reponse = new JsonResponse(array(
            'id'=>$event->getId(),
            'nom'=>$event->getNom(),
            'description'=>$event->getDescription(),
            'location'=>$event->getLocation(),
            'type'=>$event->getType()->getLibelle()
        ));
        return $reponse;

    }

    public function deleteEventAction(Request $request)
    {
        $idEvent=$request->get('idEvent');
        $em = $this->getDoctrine()->getManager();

        $event=$em->getRepository('TodoListBundle:Event')->find($idEvent);

        $em->remove($event);
        $em->flush();
        $reponse =new JsonResponse(array('isDeleted'=>true));
        return $reponse;

    }
}

