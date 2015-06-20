<?php

namespace CTO\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/{name}")
     * @Template()
     * @param string $name
     * @return array
     */
    public function indexAction($name)
    {
        return ['name' => $name];
    }
}
