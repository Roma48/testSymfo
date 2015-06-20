<?php

namespace CTO\AppBundle\Controller;

use CTO\AppBundle\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="login_home")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $a = 1;
            $b = 2;

            return $this->redirect($this->generateUrl('after_login_switch_route'));
        }

        return [];
    }

    /**
     * @Route("/admin/", name="after_login_switch_route")
     */
    public function defaultAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {

            $a = 1;
            $b = 2;

            return [];
//            return $this->redirect($this->generateUrl('admin_apps_list'));
        }

        return [];
    }

    /**
     * @Route("/login", name="login")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        $form = $this->createForm(new LoginType(), null,
            ['action' => $this->generateUrl('login_check'), 'attr' => ['novalidate' => 'novalidate']]);

        return array(
            'form' => $form->createView(),
            'error' => $error,
        );
    }
}
