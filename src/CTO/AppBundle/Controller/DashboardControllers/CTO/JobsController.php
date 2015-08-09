<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\Notification;
use CTO\AppBundle\Form\CarJobType;
use CTO\AppBundle\Form\CtoCarJobFilterType;
use CTO\AppBundle\Form\JobNotificationReminderType;
use Doctrine\ORM\EntityManager;
use Mcfedr\JsonFormBundle\Controller\JsonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/jobs")
 */
class JobsController extends JsonController
{
    /**
     * @Route("/", name="cto_jobs_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        $now = Carbon::now();
        $startMonth = $now->copy();
        $startMonth->startOfMonth();
        $endMonth = $now->copy();
        $endMonth->endOfMonth();
        /** @var CtoUser $user */
        $user = $this->getUser();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $finReport = $em->getRepository("CTOAppBundle:CarJob")->totalFinancialReportForMonth($startMonth, $endMonth, $user);

        return [
            'now' => $now,
            'jobsCount' => $finReport['jobs'],
            'repairCars' => $finReport['cars'],
            'money' => $finReport['money']
        ];
    }

    /**
     * @Route("/list", name="cto_jobs_list", options={"expose" = true})
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CtoUser $cto */
        $cto = $this->getUser();
        $jobsResult = $em->getRepository("CTOAppBundle:CarJob")->listJobsWithSortings($cto);

        $paginator = $this->get('knp_paginator');
        $jobs = $paginator->paginate(
            $jobsResult,
            $this->get('request')->query->get('page', 1),   /* page number */
            $this->container->getParameter('pagination')    /* limit per page */
        );

        $filterForm = $this->createForm(new CtoCarJobFilterType());

        return [
            "jobs" => $jobs,
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
        /** @var CtoUser $cto */
        $cto = $this->getUser();

        $jobs = [];
        $filteredJobs = [];
        if ($filterFormData) {
            $filteredJobs = $em->getRepository('CTOAppBundle:CarJob')->jobsFilter($filterFormData, $cto);
        } else {
            $withPaginator = true;
            $jobsResult = $em->getRepository("CTOAppBundle:CarJob")->listJobsWithSortings($cto);

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
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/new", name="cto_jobs_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {

        return [
            'title' => 'Нове завдання',
            'back' => 'new'
        ];
    }

    /**
     * @Route("/newFromJsonForm", name="cto_jobs_new_fromJSONFORM", options={"expose" = true})
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function createNewFromJsonFormAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $carJob = new CarJob();
        $form = $this->createForm(new CarJobType($em), $carJob);

        if ($request->getMethod() == "POST") {

            $this->handleJsonForm($form, $request);

            $carJob->getClient()->setLastVisitDate(new \DateTime('now'));
            $carJob->setTmpHash(uniqid("", true));

            $em->persist($carJob);
            $em->flush();
            $em->flush();  // <--- this flush need for calculate total cost in event listener

            $this->addFlash('success', "Завдання успішно створено.");

            return new JsonResponse(["status" => "ok", "jid" => $carJob->getId()]);
        }

        return new JsonResponse(["status" => "fail"]);
    }

    /**
     * @Route("/edit/{id}", name="cto_jobs_edit", options={"expose" = true})
     * @Method("GET")
     * @Template()
     * @param CarJob $carJob
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(CarJob $carJob)
    {

        return [
            'job' => $carJob->jsonSerialize(),
            'jobId' => $carJob->getId(),
            'title' => 'Редагування завдання',
            'back' => $carJob->getId()
        ];
    }

    /**
     * @Route("/editJsonForm/{id}", name="cto_jobs_edit_fromJSONFORM", options={"expose" = true})
     * @Method("POST")
     * @param CarJob $carJob
     * @param Request $request
     * @return JsonResponse
     */
    public function editFromJsonFormAction(CarJob $carJob, Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new CarJobType($em, $carJob->getClient()), $carJob);

        if ($request->getMethod() == "POST") {

            $this->handleJsonForm($form, $request);
            $carJob->getClient()->setLastVisitDate(new \DateTime('now'));
            $carJob->setTmpHash(uniqid("", true));

            $em->flush();

            $this->addFlash('success', "Завдання успішно відредаговано.");

            return new JsonResponse(["status" => "ok"]);
        }

        return new JsonResponse(["status" => "fail"]);
    }

    /**
     * @Route("/show/{id}", name="cto_jobs_show", options={"expose" = true})
     * @Method("GET")
     * @Template()
     * @param CarJob $carJob
     * @return array
     */
    public function showAction(CarJob $carJob)
    {

        return [
            'job' => $carJob
        ];
    }

    /**
     * @param CarJob $carJob
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/show/{jobId}/reminder", name="cto_jobs_addReminder")
     * @Method({"GET", "POST"})
     * @ParamConverter("carJob", class="CTOAppBundle:CarJob", options={"id"="jobId"})
     * @Template()
     */
    public function addReminderAction(CarJob $carJob, Request $request)
    {
        $notification = new Notification();
        $notification
            ->setType(Notification::TYPE_NOTIFICATION)
            ->setStatus(Notification::STATUS_SEND_IN_PROGRESS)
            ->setCarJob($carJob)
            ->setClientCto($carJob->getClient());

        $form = $this->createForm(new JobNotificationReminderType($carJob), $notification);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->persist($notification);


                if ($notification->isAutoSending()) {
                    $senderSrv = $this->get('cto.sms.sender');
                    /** @var CtoUser $admin */
                    $admin = $this->getUser();

                    if ($notification->isSendNow()) {
                        $senderSrv->sendNow($notification, $carJob->getClient(), $admin);
                    } else {
                        $jobDescription = $senderSrv->getResqueManager()->put('cto.sms.sender', [
                            'notificationId' => $notification->getId(),
                            'clientId' => $carJob->getClient()->getId(),
                            'adminId' => $admin->getId()
                        ], $this->getParameter('queue_name'), $notification->getWhenSend());
                        $notification->setResqueJobDescription($jobDescription);
                    }
                }

                $em->flush();

                $this->addFlash('success', 'Нагадування успішно створено.');

                return $this->redirectToRoute('cto_jobs_show', ['id' => $carJob->getId()]);
            }
        }

        return [
            'jobId' => $carJob->getId(),
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/print/{id}", name="cto_jobs_print")
     * @Method("GET")
     * @Template()
     * @param CarJob $carJob
     * @return array
     */
    public function printAction(CarJob $carJob)
    {
        $today = Carbon::now();

        return [
            'today' => $today,
            'job' => $carJob
        ];
    }
}
