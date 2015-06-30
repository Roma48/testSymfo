<?php

namespace CTO\AppBundle\Controller\DashboardControllers;

use CTO\AppBundle\Entity\CtoClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController extends Controller
{
    /**
     * @Route("/cto/ajax/carsfromclient/{id}", name="ajax_cto_cars_from_client", options={"expose" = true})
     * @Method("POST")
     */
    public function getCarFromClientAction(CtoClient $ctoClient)
    {
        $cars = $ctoClient->getCars()->getValues();

        return new JsonResponse(['cars' => $cars]);
    }

}
