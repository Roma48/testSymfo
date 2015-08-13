<?php

namespace CTO\AppBundle\Controller\DashboardControllers;

use Carbon\Carbon;
use CTO\AppBundle\Entity\AdminUser;
use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Entity\CtoClient;
use CTO\AppBundle\Entity\CtoUser;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController extends Controller
{
    /**
     * @Route("/cto/ajax/carsfromclient/{id}", name="ajax_cto_cars_from_client", options={"expose" = true})
     * @Method("GET")
     */
    public function getCarFromClientAction(CtoClient $ctoClient)
    {
        $cars = $ctoClient->getCars()->getValues();

        return new JsonResponse(['cars' => $cars]);
    }

    /**
     * @Route("/cto/ajax/getctoclients", name="ajax_cto_get_clients", options={"expose" = true})
     * @Method("GET")
     */
    public function getAllCtoClientsAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $ctoClients = $em->getRepository("CTOAppBundle:CtoClient")->findBy(['cto' => $this->getUser()]);

        return new JsonResponse(["clients" => $ctoClients]);
    }

    /**
     * @Route("/cto/ajax/getjobcategories", name="ajax_cto_get_jobCategories", options={"expose" = true})
     * @Method("GET")
     */
    public function getAllJobCategoriesAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository("CTOAppBundle:JobCategory")->findAll();

        return new JsonResponse(["categories" => $categories]);
    }

    /**
     * @Route("/cto/ajax/getJob/{id}", name="ajax_cto_getJobById", options={"expose" = true})
     * @Method("GET")
     */
    public function getJob(CarJob $carJob)
    {
        return new JsonResponse(["job" => $carJob]);
    }

    /**
     * @Route("/getAllModels", name="cto_models_getAllListForModal", options={"expose"=true})
     * @Method("POST")
     */
    public function getModelsAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $models = $em->getRepository("CTOAppBundle:Model")->findAll();

        return new JsonResponse(['models' => $models]);
    }

    /**
     * @Route("/getBadgeNotificationForLastThreeDays", name="ajax_notification_badgeLast3days", options={"expose"=true})
     * @Method("GET")
     */
    public function getBadgeNotificationForLastThreeDaysAction()
    {
        $now = Carbon::now();
        $from = $now->copy();
        $to = $now->copy()->endOfDay();

        /** @var CtoUser $user */
        $user = $this->getUser();

        if ($user instanceof AdminUser) {

            return new JsonResponse(['nitificationsCount' => 0]);
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $notificationCount = $em->getRepository("CTOAppBundle:Notification")->getNotificationCountForLast3day($user, $from, $to);

        return new JsonResponse(['nitificationsCount' => (int)$notificationCount['NotifCount']]);
    }

    /**
     * @Route("/notifications/users", name="cto_notification_broadcastGetUsersAjax", options={"expose"=true})
     * @Method("GET")
     */
    public function getUsersForBroadcastAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var CtoUser $user */
        $user = $this->getUser();
        $users = $em->getRepository('CTOAppBundle:CtoClient')->clientFilter([], $user);

        return new JsonResponse(['users' => $users]);
    }
}
