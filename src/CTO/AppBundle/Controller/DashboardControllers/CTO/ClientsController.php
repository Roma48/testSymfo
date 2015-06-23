<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cto/clients")
 */
class ClientsController extends Controller
{
    /**
     * @Route("/", name="cto_clients_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }


}