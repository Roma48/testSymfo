<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Form\ClientCarsFilterType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ClientCarsController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 *
 * @Route("/clientcars")
 */
class ClientCarsController extends Controller
{
    /**
     * @Route("/", name="cto_clientcars_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CtoUser $cto */
        $cto = $this->getUser();
        $clientCarsResult = $em->getRepository("CTOAppBundle:ClientCar")->listAllCarsWithSorting($cto);

        $paginator = $this->get('knp_paginator');
        $clientCars = $paginator->paginate(
            $clientCarsResult,
            $this->get('request')->query->get('page', 1),   /* page number */
            $this->container->getParameter('pagination')    /* limit per page */
        );

        $filterForm = $this->createForm(new ClientCarsFilterType());

        return [
            'cars' => $clientCars,
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/filter", name="cto_clientcars_filter")
     * @Method({"POST", "GET"})
     * @Template()
     * @param Request $request
     * @return array
     */
    public function filterAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var Array $filterFormData */
        $filterFormData = $request->get('clientcars_filter', null);
        if ($filterFormData) {
            $filterFormData = array_filter($filterFormData);
            if (array_key_exists('model', $filterFormData)) {
                $filterFormData['model'] = $em->getRepository('CTOAppBundle:Model')->find($filterFormData['model']);
            }
        }

        $withPaginator = false;

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CtoUser $cto */
        $cto = $this->getUser();
        $clientCars = [];

        if ($filterFormData) {
            $clientCarsResult = $em->getRepository('CTOAppBundle:ClientCar')->carFilter($filterFormData, $cto);
        } else {
            $withPaginator = true;
            $clientCarsResult = $em->getRepository("CTOAppBundle:ClientCar")->listAllCarsWithSorting($cto);

            $paginator = $this->get('knp_paginator');
            $clientCars = $paginator->paginate(
                $clientCarsResult,
                $this->get('request')->query->get('page', 1),   /* page number */
                $this->container->getParameter('pagination')    /* limit per page */
            );
        }

        $filterForm = $this->createForm(new ClientCarsFilterType(), $filterFormData);


        return [
            'cars' => $withPaginator ? $clientCars : $clientCarsResult,
            'paginator' => $withPaginator,
            'filterForm' => $filterForm->createView()
        ];
    }

}
