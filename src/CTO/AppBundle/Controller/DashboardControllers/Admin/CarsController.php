<?php

namespace CTO\AppBundle\Controller\DashboardControllers\Admin;

use CTO\AppBundle\Entity\Car;
use CTO\AppBundle\Entity\Model;
use CTO\AppBundle\Form\CarType;
use CTO\AppBundle\Form\ModelType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cars")
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
        $cars = $em->getRepository('CTOAppBundle:Car')->findBy([], ['name' => 'ASC']);

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
    public function newCarAction(Request $request)
    {
        $car = new Car();
        $form = $this->createForm(new CarType(), $car);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->persist($car);
                $em->flush();

                $this->addFlash('success', "{$car->getName()} успішно створено.");

                return $this->redirect($this->generateUrl('cto_cars_home'));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/models/new/{slug}", name="cto_cars_models_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Template()
     * @ParamConverter("car", class="CTOAppBundle:Car", options={"slug" = "slug"})
     */
    public function newModelAction(Request $request, Car $car)
    {
        $model = new Model();
        $form = $this->createForm(new ModelType(), $model);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $model->setCar($car);
                $em->persist($model);
                $em->flush();

                $this->addFlash('success', "{$model->getName()} успішно створено.");

                return $this->redirect($this->generateUrl('cto_cars_home', ['tabName' => 'models']));
            }
        }

        return [
            'form' => $form->createView()
        ];
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
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $models = $em->getRepository('CTOAppBundle:Model')->findBy(['car' => $car], ['name' => 'ASC']);

        return new JsonResponse($models);
    }

    /**
     * @Route("/edit/{slug}", name="cto_cars_edit")
     * @Method({"GET", "POST"})
     * @Template()
     * @ParamConverter("car", class="CTOAppBundle:Car", options={"slug" = "slug"})
     * @param Car $car
     * @return array
     */
    public function editCarAction(Request $request, Car $car)
    {
        $form = $this->createForm(new CarType(), $car);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->addFlash('success', "{$car->getName()} успішно відредаговано.");

                return $this->redirect($this->generateUrl('cto_cars_home'));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/model/edit/{slug}", name="cto_cars_model_edit", options={"expose"=true})
     * @Method({"GET","POST"})
     * @ParamConverter("model", class="CTOAppBundle:Model", options={"slug" = "slug"})
     * @Template()
     */
    public function editModelByCarAction(Request $request, Model $model)
    {
        $form = $this->createForm(new ModelType(), $model);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->addFlash('success', "{$model->getName()} успішно відредаговано.");

                return $this->redirect($this->generateUrl('cto_cars_home', ['tabName' => 'models']));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }
}