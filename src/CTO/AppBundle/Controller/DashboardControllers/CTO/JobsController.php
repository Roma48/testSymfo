<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Form\CarJobType;
use CTO\AppBundle\Form\CtoCarJobFilterType;
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
     * @Route("/{tabName}", name="cto_jobs_home", defaults={"tabName" = "info"}, requirements={"tabName" = "info|list"})
     * @Method("GET")
     * @Template()
     */
    public function homeAction($tabName)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $jobsResult = $em->getRepository("CTOAppBundle:CarJob")->listJobsWithSortings();

        $paginator = $this->get('knp_paginator');
        $jobs = $paginator->paginate(
            $jobsResult,
            $this->get('request')->query->get('page', 1),   /* page number */
            $this->container->getParameter('pagination')    /* limit per page */
        );

        $filterForm = $this->createForm(new CtoCarJobFilterType());

        return [
            "jobs" => $jobs,
            "tabName" => $tabName,
            "filterForm" => $filterForm->createView()
        ];
    }

    /**
     * @Route("/filter", name="cto_jobs_filter")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array
     */
    public function jobsFilterAction(Request $request)
    {
        /** @var Array $filterFormData */
        $filterFormData = $request->get('job_filter', null);
        if ($filterFormData) {
            $filterFormData = array_filter($filterFormData);
        }

        $withPaginator = false;

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if ($filterFormData) {
            if (array_key_exists('jobCategory', $filterFormData)) {
                $jobCategory = $em->getRepository('CTOAppBundle:JobCategory')->find($filterFormData['jobCategory']);
                if ($jobCategory) {
                    $filterFormData['jobCategory'] = $jobCategory;
                }
            }

            $filteredJobs = $em->getRepository('CTOAppBundle:CarJob')->jobsFilter($filterFormData);
        } else {
            $withPaginator = true;
            $jobsResult = $em->getRepository("CTOAppBundle:CarJob")->listJobsWithSortings();

            $paginator = $this->get('knp_paginator');
            $jobs = $paginator->paginate(
                $jobsResult,
                $this->get('request')->query->get('page', 1),   /* page number */
                $this->container->getParameter('pagination')    /* limit per page */
            );
        }

        $filterForm = $this->createForm(new CtoCarJobFilterType(), $filterFormData);

        return [
            'jobs' => $withPaginator ? $jobs : $filteredJobs,
            'paginator' => $withPaginator,
            "tabName" => 'list',
            'filterForm' => $filterForm->createView()
        ];
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

                $this->addFlash('success', "Завдання успішно відредаговано.");

                return $this->redirect($this->generateUrl('cto_jobs_home'));
            }
        }

        return [
            'form' => $form->createView()
        ];
    }
}