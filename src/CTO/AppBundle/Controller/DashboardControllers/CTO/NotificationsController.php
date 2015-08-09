<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CtoUser;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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



}
