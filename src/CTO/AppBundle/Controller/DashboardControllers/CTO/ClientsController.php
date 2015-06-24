<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CtoClient;
use CTO\AppBundle\Form\CtoClientType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
    public function newAction()
    {
        $client = new CtoClient();
        $form = $this->createForm(new CtoClientType(), $client);

        return [
            'form' => $form->createView()
        ];
    }
}
