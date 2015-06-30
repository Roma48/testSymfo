<?php

namespace CTO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CtoCarJobFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', 'text', [
                'attr' => ['placeholder' => 'Клієнт', 'class' => 'form-control']
            ])
            ->add("jobCategory", 'entity', [
                'class' => "CTOAppBundle:JobCategory",
                'property' => 'name',
                'attr'  => [
                    'class'         => 'selectpicker',
                    'data-width'    => "auto",
                    'data-size'     => "15",
                    'data-live-search' => true
                ],
            ])
            ->add('dateFrom', 'date', [
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => ['placeholder' => 'Дата ремонту З', 'class' => 'form-control']
            ])
            ->add('dateTo', 'date', [
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => ['placeholder' => 'Дата ремонту ПО', 'class' => 'form-control']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "job_filter";
    }
}
