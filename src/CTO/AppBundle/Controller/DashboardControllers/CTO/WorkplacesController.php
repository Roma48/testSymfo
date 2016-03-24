<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\Workplace;
use CTO\AppBundle\Form\WorkplaceType;
use Mcfedr\JsonFormBundle\Controller\JsonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WorkplacesController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 * @Route("/workplaces")
 */
class WorkplacesController extends JsonController
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/new/FromJsonForm", name="cto_new_workplace_fromJSONFORM", options={"expose" = true})
     * @Method("POST")
     */
    public function newFromJsonFormAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $workplace = new Workplace();
        $form = $this->createForm(new WorkplaceType($em), $workplace);

        if ($request->getMethod() == "POST") {

            $this->handleJsonForm($form, $request);

            $user = $this->getUser();
            $workplace->setCto($user);

            $em->persist($workplace);
            $em->flush();

            $this->addFlash('success', "Робоче місце успішно створено.");

            return new JsonResponse(["status" => "ok", "eid" => $workplace->getId()]);
        }

        return new JsonResponse(["status" => "fail"]);
    }
}