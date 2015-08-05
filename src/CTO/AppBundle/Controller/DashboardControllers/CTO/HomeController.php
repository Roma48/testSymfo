<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Form\CtoUserType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HomeController
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="ctoUser_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        return [];
    }

    /**
     * @Route("/settings", name="ctoUser_settings_edit")
     * @Method({"POST", "GET"})
     * @Template("@CTOApp/DashboardControllers/Admin/Cto/new.html.twig")
     */
    public function settingsAction(Request $request)
    {
        /** @var CtoUser $user */
        $user = $this->getUser();
        $form = $this->createForm(new CtoUserType(), $user);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                if ($user->getPlainPassword()) {
                    $encoder = $this->get('security.password_encoder');
                    $user->setPassword($encoder->encodePassword($user, $user->getPlainPassword()));
                }

                $em->flush();

                $this->addFlash('success', "{$user->getCtoName()} успішно відредаговано.");

                return $this->redirectToRoute('cto_jobs_home');
            }
        }

        return [
            'form' => $form->createView(),
            'title' => 'Редагувати'
        ];

    }

    /**
     * @Route("/finance", name="ctoUser_financial_story")
     * @Method("GET")
     * @Template()
     */
    public function financialArchiveAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CtoUser $user */
        $user = $this->getUser();

        $result = [];
        $firstJob = $em->getRepository('CTOAppBundle:CarJob')->getOneJobByCTOUser($user, 'ASC');
        $lastJob = $em->getRepository('CTOAppBundle:CarJob')->getOneJobByCTOUser($user, 'DESC');

        if (count($firstJob) == 0 and count($lastJob) == 0) {

            return ['results' => $result];
        }

        $firstJobDate = Carbon::createFromFormat('Y-m-d', $firstJob[0]->getJobDate()->format('Y-m-d'));
        $lastJobDate = Carbon::createFromFormat('Y-m-d', $lastJob[0]->getJobDate()->format('Y-m-d'));

        while ($firstJobDate <= $lastJobDate ) {
            $month = $this->getStartAndFinishMonth($firstJobDate);
            $finReport = $em->getRepository("CTOAppBundle:CarJob")->totalFinancialReportForMonth($month['start'], $month['finish'], $user);

            $result[] = [
                'date' => $month['start'],
                'jobsCount' => $finReport['jobs'],
                'repairCars' => $finReport['cars'],
                'money' => $finReport['money']
            ];

            $firstJobDate->addMonth();
        }

        return ['results' => $result];
    }

    private function getStartAndFinishMonth(DateTime $date)
    {
        $now = Carbon::createFromFormat('Y-m-d', $date->format('Y-m-d'));

        $startMonth = $now->copy();
        $startMonth->startOfMonth();
        $endMonth = $now->copy();
        $endMonth->endOfMonth();

        return [
            'start' => $startMonth,
            'finish' => $endMonth
        ];
    }
}
