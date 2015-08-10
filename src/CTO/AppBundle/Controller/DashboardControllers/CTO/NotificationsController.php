<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\Notification;
use CTO\AppBundle\Form\JobNotificationReminderType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/notifications" )
 */
class NotificationsController extends Controller
{
    /**
     * @Route("/{tabName}", name="cto_notification_home", defaults={"tabName"="current"}, requirements={"tabName" = "current|planned|sentout"})
     * @Method("GET")
     * @Template()
     */
    public function homeAction($tabName)
    {
        $now = Carbon::now();
        $from = $now->copy()->startOfDay();
        $to = $now->copy()->endOfDay();
        $plannedFrom = $now->copy()->addDay()->startOfDay();


        /** @var CtoUser $user */
        $user = $this->getUser();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $currentsR = $em->getRepository('CTOAppBundle:Notification')->getCurrents($user, $from, $to);
        $plannedR = $em->getRepository('CTOAppBundle:Notification')->getPlanned($user, $plannedFrom);
        $sentOutR = $em->getRepository('CTOAppBundle:Notification')->getSentOut($user);

        $paginator = $this->get('knp_paginator');

        $currents = $paginator->paginate(
            $currentsR,
            $this->get('request')->query->get('page', 1),   /*page number*/
            $this->container->getParameter('pagination')    /*limit per page*/
        );
        $planned = $paginator->paginate(
            $plannedR,
            $this->get('request')->query->get('page', 1),   /*page number*/
            $this->container->getParameter('pagination')    /*limit per page*/
        );
        $sentOut = $paginator->paginate(
            $sentOutR,
            $this->get('request')->query->get('page', 1),   /*page number*/
            $this->container->getParameter('pagination')    /*limit per page*/
        );

        return [
            'tabName' => $tabName,
            'currents' => $currents,
            'planned' => $planned,
            'sentout' => $sentOut
        ];
    }

    /**
     * @param Notification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/skip/{id}", name="cto_notification_skip");
     * @Method("GET")
     */
    public function skip(Notification $notification)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($notification->getResqueJobDescription()) {
            $this->get('cto.sms.sender')->stop($notification->getResqueJobDescription());
        }

        $notification
            ->setStatus(Notification::STATUS_ABORTED);
        $em->flush();

        $this->addFlash('success', 'Нагадування успішно відмінено.');

        return $this->redirectToRoute('cto_notification_home');
    }

    /**
     * @param Notification $notification
     * @param Request $request
     * @return array
     *
     * @Route("/edit/{id}", name="cto_notification_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Notification $notification, Request $request)
    {
        /** @var CarJob $carJob */
        $carJob = $notification->getCarJob();
        $form = $this->createForm(new JobNotificationReminderType($carJob), $notification);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $this->get('cto.sms.sender')->stop($notification->getResqueJobDescription());

                if ($notification->isAutoSending()) {
                    $senderSrv = $this->get('cto.sms.sender');
                    /** @var CtoUser $admin */
                    $admin = $this->getUser();

                    if ($notification->isSendNow()) {
                        $senderSrv->sendNow($notification, $carJob->getClient(), $admin);
                    } else {
                        $jobDescription = $senderSrv->getResqueManager()->put('cto.sms.sender', [
                            'notificationId' => $notification->getId(),
                            'clientId' => $carJob->getClient()->getId(),
                            'adminId' => $admin->getId()
                        ], $this->getParameter('queue_name'), $notification->getWhenSend());
                        $notification->setResqueJobDescription($jobDescription);
                    }
                }
                $em->flush();
                $this->addFlash('success', 'Нагадування успішно відредаговано.');

                return $this->redirectToRoute('cto_notification_home');
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Notification $notification
     * @param Request $request
     * @return array
     *
     * @Route("/copy/{id}", name="cto_notification_copy")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function copyAction(Notification $notification, Request $request)
    {
        $newNotification = new Notification();
        $newNotification
            ->setStatus(Notification::STATUS_SEND_IN_PROGRESS)
            ->setAdminCopy($notification->isAdminCopy())
            ->setAutoSending($notification->isAutoSending())
            ->setCarJob($notification->getCarJob())
            ->setCarJobCategory($notification->getCarJobCategory())
            ->setClientCto($notification->getClientCto())
            ->setWhenSend($notification->getWhenSend())
            ->setType(Notification::TYPE_NOTIFICATION)
            ->setDescription($notification->getDescription());

        /** @var CarJob $carJob */
        $carJob = $notification->getCarJob();
        $form = $this->createForm(new JobNotificationReminderType($carJob), $newNotification);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->persist($newNotification);
                $em->flush();

                if ($newNotification->isAutoSending()) {
                    $senderSrv = $this->get('cto.sms.sender');
                    /** @var CtoUser $admin */
                    $admin = $this->getUser();

                    if ($newNotification->isSendNow()) {
                        $senderSrv->sendNow($newNotification, $carJob->getClient(), $admin);
                    } else {
                        $jobDescription = $senderSrv->getResqueManager()->put('cto.sms.sender', [
                            'notificationId' => $newNotification->getId(),
                            'clientId' => $carJob->getClient()->getId(),
                            'adminId' => $admin->getId()
                        ], $this->getParameter('queue_name'), $newNotification->getWhenSend());
                        $newNotification->setResqueJobDescription($jobDescription);
                    }
                }
                $em->flush();
                $this->addFlash('success', 'Нагадування успішно створено.');

                return $this->redirectToRoute('cto_notification_home');
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

}
