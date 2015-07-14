<?php

namespace CTO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCarsFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', 'text', [
                'attr' => ['placeholder' => 'Клієнт', 'class' => 'form-control']
            ])
            ->add("model", 'entity', [
                'class' => "CTOAppBundle:Model",
                'empty_value' => "Не вибрано",
                'property' => 'name',
                'attr'  => [
                    'class'         => 'selectpicker',
                    'data-width'    => "15%",
                    'data-size'     => "15",
                    'data-live-search' => true
                ],
            ])
            ->add('dateFrom', 'text', [
                'attr' => ['placeholder' => 'Дата візиту З', 'class' => 'form-control date-picker-cto']
            ])
            ->add('dateTo', 'text', [
                'attr' => ['placeholder' => 'Дата візиту ПО', 'class' => 'form-control date-picker-cto']
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
        return "clientcars_filter";
    }
}
