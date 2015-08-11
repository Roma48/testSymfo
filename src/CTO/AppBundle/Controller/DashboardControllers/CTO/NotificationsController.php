<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Entity\CtoClient;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\DTO\Broadcast;
use CTO\AppBundle\Entity\Notification;
use CTO\AppBundle\Form\DTO\BroadcastType;
use CTO\AppBundle\Form\BroadcastType as BroadcastEditType;
use CTO\AppBundle\Form\JobNotificationReminderType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        /** @var CtoUser $user */
        $user = $this->getUser();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $currentsR = $em->getRepository('CTOAppBundle:Notification')->getCurrents($user, $from, $to);
        $plannedR = $em->getRepository('CTOAppBundle:Notification')->getPlanned($user, $from, $to);
        $sentOutR = $em->getRepository('CTOAppBundle:Notification')->getSentOut($user);

        $paginator = $this->get('knp_paginator');

        $currents = $paginator->paginate(
            $currentsR,
            $this->get('request')->query->get('page', 1),
            $this->container->getParameter('pagination')
        );
        $planned = $paginator->paginate(
            $plannedR,
            $this->get('request')->query->get('page', 1),
            $this->container->getParameter('pagination')
        );
        $sentOut = $paginator->paginate(
            $sentOutR,
            $this->get('request')->query->get('page', 1),
            $this->container->getParameter('pagination')
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

    /**
     * @param Request $request
     * @return array
     *
     * @Route("/broadcast", name="cto_notification_broadcast")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function broadcastAction(Request $request)
    {
        $broadcastDto = new Broadcast();
        $form = $this->createForm(new BroadcastType(), $broadcastDto);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $userIds = explode(',', $broadcastDto->getTo());
                $users = $em->getRepository('CTOAppBundle:CtoClient')->findBy(['id' => $userIds]);

                /** @var CtoClient $client */
                foreach ($users as $client) {
                    $newNotification = new Notification();
                    $newNotification
                        ->setStatus(Notification::STATUS_SEND_IN_PROGRESS)
                        ->setAdminCopy($broadcastDto->isAdminCopy())
                        ->setAutoSending($broadcastDto->isAutoSending())
                        ->setClientCto($client)
                        ->setWhenSend($broadcastDto->getWhen())
                        ->setType(Notification::TYPE_BROADCAST)
                        ->setDescription($broadcastDto->getDescription());
                    $em->persist($newNotification);
                    $em->flush();

                    if ($newNotification->isAutoSending()) {
                        $senderSrv = $this->get('cto.sms.sender');
                        /** @var CtoUser $admin */
                        $admin = $this->getUser();

                        if ($newNotification->isSendNow()) {
                            $senderSrv->sendNow($newNotification, $client, $admin);
                        } else {
                            $jobDescription = $senderSrv->getResqueManager()->put('cto.sms.sender', [
                                'notificationId' => $newNotification->getId(),
                                'clientId' => $client->getId(),
                                'adminId' => $admin->getId()
                            ], $this->getParameter('queue_name'), $newNotification->getWhenSend());
                            $newNotification->setResqueJobDescription($jobDescription);
                        }
                    }
                    $em->flush();
                } // end foreach

                $this->addFlash('success', 'Розсилка успішно створена.');

                return $this->redirectToRoute('cto_notification_home');
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @param Notification $notification
     * @return array
     *
     * @Route("/broadcast/edit/{id}", name="cto_notification_broadcastEdit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function broadcastEditAction(Request $request, Notification $notification)
    {
        /** @var CtoUser $admin */
        $admin = $this->getUser();
        $form = $this->createForm(new BroadcastEditType($admin), $notification);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $this->get('cto.sms.sender')->stop($notification->getResqueJobDescription());

                if ($notification->isAutoSending()) {
                    $senderSrv = $this->get('cto.sms.sender');

                    if ($notification->isSendNow()) {
                        $senderSrv->sendNow($notification, $notification->getClientCto(), $admin);
                    } else {
                        $jobDescription = $senderSrv->getResqueManager()->put('cto.sms.sender', [
                            'notificationId' => $notification->getId(),
                            'clientId' => $notification->getClientCto()->getId(),
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
     * @param Request $request
     * @param Notification $notification
     * @return array
     *
     * @Route("/broadcast/copy/{id}", name="cto_notification_broadcastCopy")
     * @Method({"GET", "POST"})
     * @Template("@CTOApp/DashboardControllers/CTO/Notifications/broadcastEdit.html.twig")
     */
    public function broadcastCopyAction(Request $request, Notification $notification)
    {
        $newNotification = new Notification();
        $newNotification
            ->setStatus(Notification::STATUS_SEND_IN_PROGRESS)
            ->setAdminCopy($notification->isAdminCopy())
            ->setAutoSending($notification->isAutoSending())
            ->setClientCto($notification->getClientCto())
            ->setWhenSend($notification->getWhenSend())
            ->setType(Notification::TYPE_BROADCAST)
            ->setDescription($notification->getDescription());

        /** @var CtoUser $admin */
        $admin = $this->getUser();
        $form = $this->createForm(new BroadcastEditType($admin), $newNotification);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->persist($newNotification);
                $em->flush();

                if ($newNotification->isAutoSending()) {
                    $senderSrv = $this->get('cto.sms.sender');

                    if ($newNotification->isSendNow()) {
                        $senderSrv->sendNow($newNotification, $newNotification->getClientCto(), $admin);
                    } else {
                        $jobDescription = $senderSrv->getResqueManager()->put('cto.sms.sender', [
                            'notificationId' => $newNotification->getId(),
                            'clientId' => $newNotification->getClientCto()->getId(),
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

    /**
     * @Route("/users", name="cto_notification_broadcastGetUsersAjax", options={"expose"=true})
     * @Method("GET")
     */
    public function getUsersForBroadcastAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CtoUser $user */
        $user = $this->getUser();
        $users = $em->getRepository('CTOAppBundle:CtoClient')->clientFilter([], $user);

        return new JsonResponse(['users' => $users]);
    }
}
