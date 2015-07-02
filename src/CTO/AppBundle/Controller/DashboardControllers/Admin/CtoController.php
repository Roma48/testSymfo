<?php

namespace CTO\AppBundle\Controller\DashboardControllers\Admin;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 * @Route("/cto")
 */
class CtoController extends Controller
{
    /**
     * @Route("/clients", name="admin_ctoUsers_list")
     * @Method("GET")
     * @Template()
     */
    public function ctoClientsAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $ctoUsers = $em->getRepository("CTOAppBundle:CtoUser")->findAll();
        $paginator = $this->get('knp_paginator');
        $clients = $paginator->paginate(
            $ctoUsers,
            $this->get('request')->query->get('page', 1),   /* page number */
            $this->container->getParameter('pagination')    /* limit per page */
        );

        return [
            "clients" => $clients
        ];
    }

}
