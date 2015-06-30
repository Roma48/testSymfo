<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Form\CarJobType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function newAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $carJob = new CarJob();
        $form = $this->createForm(new CarJobType($em), $carJob);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($carJob);
                $em->flush();

                $this->addFlash('success', "Завдання успішно створено.");

                return $this->redirect($this->generateUrl('cto_jobs_home'));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/edit/{id}", name="cto_jobs_edit")
     * @Method({"GET", "POST"})
     * @Template("@CTOApp/DashboardControllers/CTO/Jobs/new.html.twig")
     * @param CarJob $carJob
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(CarJob $carJob, Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CarJobType($em, $carJob->getClient()), $carJob);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($carJob);
                $em->flush();

                $this->addFlash('success', "Завдання успішно створено.");

                return $this->redirect($this->generateUrl('cto_jobs_home'));
            }
        }

        return [
            'form' => $form->createView()
        ];

    }
}