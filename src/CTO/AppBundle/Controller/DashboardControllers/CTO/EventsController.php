<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\Event;
use CTO\AppBundle\Form\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use CTO\AppBundle\Entity\CtoUser;
use Doctrine\ORM\EntityManager;
use Mcfedr\JsonFormBundle\Controller\JsonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints\Date;


/**
 * Class EventsController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 * @Route("/events")
 */
class EventsController extends JsonController
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

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/new/FromJsonForm", name="cto_new_event_fromJSONFORM", options={"expose" = true})
     * @Method("POST")
     */
    public function createNewFromJsonFormAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $event = new Event();
        $form = $this->createForm(new EventType($em), $event);

        if ($request->getMethod() == "POST") {

            $this->handleJsonForm($form, $request);

            $user = $this->getUser();
            $event->setCto($user);

            $event->setStartAt(new DateTime($event->getStartAt()));
            $event->setEndAt(new DateTime($event->getEndAt()));

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', "Подію успішно створено.");

            return new JsonResponse(["status" => "ok", "eid" => $event->getId()]);
        }

        return new JsonResponse(["status" => "fail"]);
    }

    /**
     * @param Request $request
     * @param Event $event
     * @return JsonResponse
     *
     * @Route("/{id}/edit/FromJsonForm", name="cto_edit_event_fromJSONFORM", options={"expose" = true})
     * @ParamConverter("event", class="CTOAppBundle:Event", options={"id" = "id"})
     * @Method({"POST", "GET"})
     */
    public function editFromJsonFormAction(Request $request, Event $event)
    {
        if ($request->getMethod() == "GET"){
            return new JsonResponse([
                'event' => $event
            ]);
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new EventType($em), $event);

        if ($request->getMethod() == "POST") {

            $this->handleJsonForm($form, $request);

            $user = $this->getUser();
            $event->setCto($user);

            $event->setStartAt(new DateTime($event->getStartAt()));
            $event->setEndAt(new DateTime($event->getEndAt()));

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', "Подію успішно відредаговано.");

            return new JsonResponse(["status" => "ok", "eid" => $event->getId()]);
        }

        return new JsonResponse(["status" => "fail"]);
    }

    /**
     * @param Request $request
     * @param Event $event
     * @return JsonResponse
     *
     * @Route("/{id}/delete/FromJsonForm", name="cto_delete_event_fromJSONFORM", options={"expose" = true})
     * @ParamConverter("event", class="CTOAppBundle:Event", options={"id" = "id"})
     * @Method("POST")
     */
    public function deleteFromJsonFormAction(Request $request, Event $event)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == "POST") {

            $em->remove($event);
            $em->flush();

            $this->addFlash('success', "Подію успішно видалено.");

            return new JsonResponse(["status" => "ok", "eid" => $event->getId()]);
        }

        return new JsonResponse(["status" => "fail"]);
    }


    /**
     * @param Request $request
     * @param Event $event
     *
     * @Route("/show/{id}", name="cto_show_event", options={"expose" = true})
     * @ParamConverter("event", class="CTOAppBundle:Event", options={"id" = "id"})
     * @Template()
     *
     * @return array
     */
    public function showAction(Request $request, Event $event)
    {
        return [
            'event' => $event
        ];
    }
}