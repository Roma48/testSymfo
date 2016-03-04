<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\Event;
use CTO\AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DateTime;

/**
 * Class EventsController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 * @Route("/events")
 */
class EventsController extends Controller
{
    /**
     * @Route("/new", name="cto_new_event")
     * @Template()
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                /** @var CtoUser $user */
                $user = $this->getUser();

                $event->setCto($user);

                $em->persist($event);
                $em->flush();

                $this->addFlash('success', "Подію успішно створено.");

                return $this->redirect($this->generateUrl('cto_client_home'));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }
}