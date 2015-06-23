<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\Car;
use CTO\AppBundle\Entity\Model;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/cto/cars")
 */
class CarsController extends Controller
{
    /**
     * @Route("/{tabName}", name="cto_cars_home", defaults={"tabName" = "brands"}, requirements={"tabName" = "brands|models"}, options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function homeAction($tabName)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $cars = $em->getRepository('CTOAppBundle:Car')->findAll();


        return [
            "cars" => $cars,
            "tabName" => $tabName
        ];
    }

    /**
     * @Route("/new", name="cto_cars_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newCarAction()
    {


    }

    /**
     * @Route("/models/new/{slug}", name="cto_cars_models_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @ParamConverter("car", class="CTOAppBundle:Car", options={"slug" = "slug"})
     * @param Car $car
     */
    public function newModelAction(Car $car)
    {


    }

    /**
     * @Route("/models/brand/{slug}", name="cto_cars_models_list", options={"expose"=true})
     * @ParamConverter("car", class="CTOAppBundle:Car", options={"slug" = "slug"})
     * @Method("POST")
     * @param Car $car
     * @return JsonResponse
     */
    public function getModelsByCarAction(Car $car)
    {

        return new JsonResponse($car->getModels()->getValues());
    }

    /**
     * @Route("/edit/{slug}", name="cto_cars_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @ParamConverter("car", class="CTOAppBundle:Car", options={"slug" = "slug"})
     * @param Car $car
     * @return array
     */
    public function editCarAction(Car $car)
    {
        return [
            "car" => $car
        ];
    }

    /**
     * @Route("/model/edit/{slug}", name="cto_cars_model_edit", options={"expose"=true})
     * @Method({"GET","POST"})
     * @ParamConverter("model", class="CTOAppBundle:Model", options={"slug" = "slug"})
     * @Template()
     */
    public function editModelByCarAction(Model $model)
    {

        $z = $model;

        return [];
    }


}