<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use Carbon\Carbon;
use CTO\AppBundle\Entity\CtoUser;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

        $firstJob = $em->getRepository('CTOAppBundle:CarJob')->getOneJobByCTOUser($user, 'ASC');
        $lastJob = $em->getRepository('CTOAppBundle:CarJob')->getOneJobByCTOUser($user, 'DESC');
        $firstJobDate = Carbon::createFromFormat('Y-m-d', $firstJob[0]->getJobDate()->format('Y-m-d'));
        $lastJobDate = Carbon::createFromFormat('Y-m-d', $lastJob[0]->getJobDate()->format('Y-m-d'));
        $result = [];

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
