<?php

namespace CTO\AppBundle\Controller\DashboardControllers\CTO;

use CTO\AppBundle\Entity\CtoUser;
use CTO\AppBundle\Entity\JobCategory;
use CTO\AppBundle\Form\JobCategoryType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoriesController
 * @package CTO\AppBundle\Controller\DashboardControllers\CTO
 *
 * @Route("/categories")
 */
class CategoriesController extends Controller
{
    /**
     * @Route("/", name="cto_jobcategories_home")
     * @Method("GET")
     * @Template()
     */
    public function listAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var JobCategory[] $jobCategories */
        $jobCategories = $em->getRepository("CTOAppBundle:JobCategory")->findBy(['cto' => $this->getUser()]);

        return [
            "categories" => $jobCategories
        ];
    }

    /**
     * @param Request $request
     * @return array
     *
     * @Route("/new", name="cto_jobcategories_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $jobCategory = new JobCategory();
        $form = $this->createForm(new JobCategoryType(), $jobCategory);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($jobCategory->isNormHours()) {
                if (!$jobCategory->getNormHoursPrice()) {
                    $formError = new FormError("Це поле не повинно бути пустим");
                    $form->get('normHoursPrice')->addError($formError);
                }
            }

            if ($form->isValid()) {
                /** @var CtoUser $user */
                $user = $this->getUser();
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                $jobCategory->setCto($user);
                $jobCategory->isNormHours() ? $jobCategory->setFixedPrice(null) : $jobCategory->setNormHoursPrice(null);
                $em->persist($jobCategory);
                $em->flush();
                $this->addFlash("success", "Категорію успішно створено");

                return $this->redirectToRoute("cto_jobcategories_home");
            }
        }

        return [
            "form" => $form->createView(),
            "title" => "Створити"
        ];
    }

    /**
     * @param Request $request
     * @param JobCategory $category
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/edit", name="cto_jobcategories_edit")
     * @Method({"GET", "POST"})
     * @Template("@CTOApp/DashboardControllers/CTO/Categories/new.html.twig")
     */
    public function updateAction(Request $request, JobCategory $category)
    {
        $form = $this->createForm(new JobCategoryType(), $category);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($category->isNormHours()) {
                if (!$category->getNormHoursPrice()) {
                    $formError = new FormError("Це поле не повинно бути пустим");
                    $form->get('normHoursPrice')->addError($formError);
                }
            }

            if ($form->isValid()) {
                /** @var CtoUser $user */
                $user = $this->getUser();
                /** @var EntityManager $em */
                $em = $this->getDoctrine()->getManager();

                $category->setCto($user);
                $category->isNormHours() ? $category->setFixedPrice(null) : $category->setNormHoursPrice(null);
                $em->flush();
                $this->addFlash("success", "Категорію успішно відредаговано");

                return $this->redirectToRoute("cto_jobcategories_home");
            }
        }

        return [
            "form" => $form->createView(),
            "title" => "Редагувати"
        ];
    }

    public function showAction()
    {

    }

    public function removeAction()
    {

    }
}
