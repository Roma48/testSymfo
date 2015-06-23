<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/cto/cars")
 */
class CarsController extends Controller
{
    /**
     * @Route("/", name="cto_cars_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }


}