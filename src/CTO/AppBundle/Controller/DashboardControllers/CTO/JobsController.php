<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/jobs")
 */
class JobsController extends Controller
{
    /**
     * @Route("/", name="cto_jobs_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }

    /**
     * @Route("/new", name="cto_jobs_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction()
    {
        return [];
    }
}