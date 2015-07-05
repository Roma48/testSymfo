<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CarCategory;
use CTO\AppBundle\Entity\CarJob;
use CTO\AppBundle\Entity\CategoryJobDescription;
use CTO\AppBundle\Form\CarJobType;
use Doctrine\ORM\EntityManager;
use Mcfedr\JsonFormBundle\Controller\JsonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class testContrJSONFORMController extends JsonController
{
    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/jsonf")
     * @Method("POST")
     */
    public function ffAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $carJob = new CarJob();
        $form = $this->createForm(new CarJobType($em), $carJob);

        $this->handleJsonForm($form, $request);


        $carCat = $carJob->getCarCategories();

        /** @var CarCategory $cat */
        foreach ($carCat as $cat) {
            $jobs = $cat->getJobDescriptions();
                /** @var CategoryJobDescription $job */
                foreach($jobs as $job) {
                    $jobD = $job->getDescription();
                }
        }

        $em->persist($carJob);
        $em->flush();



//        if ($request->getMethod() == "POST") {
//            $form->handleRequest($request);
//            if ($form->isValid()) {
////                $carJob->setPrice(str_replace(',', '.', $carJob->getPrice()));
//                $em->persist($carJob);
//                $em->flush();
//
//                $this->addFlash('success', "Завдання успішно створено.");
//
//                return $this->redirect($this->generateUrl('cto_jobs_list'));
//            }
//        }

        return new JsonResponse(['carJob' => $carJob->getId()], 200);
    }

}
