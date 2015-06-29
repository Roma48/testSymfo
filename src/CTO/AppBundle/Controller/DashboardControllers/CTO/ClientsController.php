<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CtoClient;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Form\CtoClientFilterType;
use CTO\AppBundle\Form\CtoClientType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/clients")
 */
class ClientsController extends Controller
{
    /**
     * @Route("/", name="cto_clients_home")
     * @Method("GET")
     * @Template()
     */
    public function homeAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $dql = 'SELECT u From CTOAppBundle:CtoClient u';
        $usersResult = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $clients = $paginator->paginate(
            $usersResult,
            $this->get('request')->query->get('page', 1),   /* page number */
            $this->container->getParameter('pagination')    /* limit per page */
        );

        $filterForm = $this->createForm(new CtoClientFilterType());

        return [
            'clients' => $clients,
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/new", name="cto_client_new")
     * @Method({"GET", "POST"})
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $client = new CtoClient();
        $form = $this->createForm(new CtoClientType(), $client);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                /** @var CtoUser $user */
                $user = $this->getUser();

                $client
                    ->setLastVisitDate(new DateTime('now'))
                    ->setCity($user->getCity());

                $user->addClient($client);
                $em->persist($client);
                $em->flush();

                $this->addFlash('success', "Клієнт {$client->getFirstName()} {$client->getLastName()} успішно створено.");

                return $this->redirect($this->generateUrl('cto_clients_home'));
            }
        }

        return [
            'info' => 'Новий клієнт:',
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/edit/{slug}", name="cto_client_edit")
     * @Method({"GET", "POST"})
     * @Template("@CTOApp/DashboardControllers/CTO/Clients/new.html.twig")
     * @ParamConverter("ctoClient", class="CTOAppBundle:CtoClient", options={"slug" = "slug"})
     * @param CtoClient $ctoClient
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(CtoClient $ctoClient, Request $request)
    {
        $form = $this->createForm(new CtoClientType(), $ctoClient);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {

                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->addFlash('success', "Клієнт {$ctoClient->getFirstName()} {$ctoClient->getLastName()} успішно відредаговано.");

                return $this->redirect($this->generateUrl('cto_clients_home'));
            }
        }

        return [
            'info' => "{$ctoClient->getFirstName()} {$ctoClient->getLastName()}: редагування",
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/filter", name="cto_client_filter")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function filterAction(Request $request)
    {
        /** @var Array $filterFormData */
        $filterFormData = $request->get('cto_client_filter', null);
        if ($filterFormData) {
            $filterFormData = array_filter($filterFormData);
        }

        $withPaginator = false;

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        if ($filterFormData) {
            $usersResult = $em->getRepository('CTOAppBundle:CtoClient')->clientFilter($filterFormData);
        } else {
            $withPaginator = true;
            $dql = 'SELECT u From CTOAppBundle:CtoClient u';
            $usersResult = $em->createQuery($dql);

            $paginator = $this->get('knp_paginator');
            $clients = $paginator->paginate(
                $usersResult,
                $this->get('request')->query->get('page', 1),   /* page number */
                $this->container->getParameter('pagination')    /* limit per page */
            );
        }

        $filterForm = $this->createForm(new CtoClientFilterType(), $filterFormData);

        return [
            'clients' => $withPaginator ? $clients : $usersResult,
            'paginator' => $withPaginator,
            'filterForm' => $filterForm->createView()
        ];
    }

    /**
     * @Route("/show/{slug}/{tabName}", name="cto_client_show", defaults={"tabName" = "info"}, requirements={"tabName" = "info|cars|jobs"})
     * @Method({"GET", "POST"})
     * @ParamConverter("ctoClient", class="CTOAppBundle:CtoClient", options={"slug" = "slug"})
     * @Template()
     * @param CtoClient $ctoClient
     * @param $tabName
     * @return array
     */
    public function showAction(CtoClient $ctoClient, $tabName)
    {
        return [
            "client" => $ctoClient,
            "tabName" => $tabName
        ];
    }
}
