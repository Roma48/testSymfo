<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\ClientCar;
use CTO\AppBundle\Entity\CtoClient;
use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Form\CtoClientType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cto/clients")
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
        $clients = $em->getRepository('CTOAppBundle:CtoClient')->findAll();

        return [
            'clients' => $clients
        ];
    }

    /**
     * @Route("/new", name="cto_client_new")
     * @Method({"GET", "POST"})
     * @Template()
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
            'form' => $form->createView()
        ];
    }
}
