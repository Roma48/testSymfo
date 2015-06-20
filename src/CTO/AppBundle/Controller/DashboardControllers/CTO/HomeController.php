<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class HomeController
 * @Route("/cto")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="ctoUser_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }
}
