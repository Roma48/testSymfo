<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\Workplace;
use CTO\AppBundle\Form\WorkplaceType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WorkplacesController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 * @Route("/workplaces")
 */
class WorkplacesController extends Controller
{
    /**
     * @Route("/", name="cto_workplaces_home")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Workplace[] $workplaces */
        $workplaces = $em->getRepository("CTOAppBundle:Workplace")->findBy(['cto' => $this->getUser()]);

        return [
            "workplaces" => $workplaces
        ];
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("/new", name="cto_workplaces_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $workplace = new Workplace();
        $form = $this->createForm(new WorkplaceType($em), $workplace);

        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);

            $user = $this->getUser();
            $workplace->setCto($user);

            $em->persist($workplace);
            $em->flush();

            $this->addFlash('success', "Робоче місце успішно створено.");

            return $this->redirectToRoute("cto_workplaces_home");
        }

        return [
            "form" => $form->createView(),
            "title" => "Створити"
        ];
    }

    /**
     * @param Request $request
     * @param Workplace $workplace
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/edit", name="cto_workplaces_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Workplace $workplace)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new WorkplaceType($em), $workplace);

        if ($request->getMethod() == "POST") {

            $form->handleRequest($request);

            $user = $this->getUser();
            $workplace->setCto($user);

            $em->persist($workplace);
            $em->flush();

            $this->addFlash('success', "Робоче місце успішно відредаговано.");

            return $this->redirectToRoute("cto_workplaces_home");
        }

        return [
            "form" => $form->createView(),
            "title" => "Редагувати"
        ];
    }

    /**
     * @param Request $request
     * @param Workplace $workplace
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/delete", name="cto_workplaces_delete")
     * @Method({"GET"})
     */
    public function deleteAction(Request $request, Workplace $workplace)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $em->remove($workplace);
        $em->flush();

        $this->addFlash('success', "Робоче місце успішно видалено.");

        return $this->redirectToRoute("cto_workplaces_home");
    }
}