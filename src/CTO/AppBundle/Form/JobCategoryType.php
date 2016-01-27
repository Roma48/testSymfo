<?php

namespace CTO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", "text", [
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Назва категорії *"
            ])
            ->add("normHours", "checkbox", [
                "label" => "Нормо-години ?"
            ])
            ->add("normHoursPrice", "text", [
                "attr" => [
                    "class" => "form-control"
                ],
                "label" => "Вартість нормо-години"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CTO\AppBundle\Entity\JobCategory',
        ]);
    }

    public function getName()
    {
        return "job_category";
    }

    public function getBlockPrefix()
    {
        return $this->getName();
    }
}