<?php

namespace CTO\AppBundle\Form;

use CTO\AppBundle\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('model', 'entity', [
                'label' => 'Модель автомобіля: *',
                'class' => 'CTOAppBundle:Model',
                'attr'  => [
                    'class'         => 'selectpicker',
                    'data-width'    => "100%",
                    'data-size'     => "15",
                    'data-live-search' => true
                ],
                'property' => 'name'

            ])
            ->add('carNumber', 'text', [
                'label'     => 'Номер автомобіля:',
                'attr'      => ['class' => 'form-control'],
                'required'  => false
            ])
            ->add('carColor', 'text', [
                'label'     => 'Колір автомобіля:',
                'attr'      => ['class' => 'form-control'],
                'required'  => false
            ]);
//            ->add('carBrand', 'entity', [
//                'label' => 'Марка Автомобіля',
//                'class' => 'CTOAppBundle:Car',
//                'attr'  => [
//                    'class'         => 'selectpicker',
//                    'data-width'    => "100%",
//                    'data-size'     => "15"
//                ],
//                'property' => 'name'
//            ]);

//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) {
//                $form = $event->getForm();
//
//                $data = $event->getData();
//
//                $carBrand = $data->getCarBrand();
//                $models = null === $carBrand ? array() : $carBrand->getModels();
//
//                $form->add('model', 'entity', array(
//                    'class'       => 'CTOAppBundle:Model',
//                    'placeholder' => '',
//                    'choices'     => $models,
//                ));
//            }
//        );

//            $formModifier = function (FormInterface $form, Car $carBrand = null) {
//                $models = null === $carBrand ? [] : $carBrand->getModels();
//                $form->add('model', 'entity', [
//                    'class'       => 'CTOAppBundle:Model',
//                    'placeholder' => '',
//                    'choices'     => $models,
//                    'attr'  => [
//                        'class'         => 'selectpicker',
//                        'data-width'    => "100%",
//                        'data-size'     => "15"
//                    ]
//                ]);
//            };
//
//            $builder->addEventListener(
//                FormEvents::PRE_SET_DATA,
//                function(FormEvent $event) use ($formModifier) {
//                    $data = $event->getData();
//
//                    $formModifier($event->getForm(), $data->getCarBrand());
//                }
//            );
//
//            $builder->get('carBrand')->addEventListener(
//                FormEvents::POST_SUBMIT,
//                function(FormEvent $event) use ($formModifier) {
//                    $carBrand = $event->getForm()->getData();
//                    $formModifier($event->getForm()->getParent(), $carBrand);
//                }
//            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CTO\AppBundle\Entity\ClientCar'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'client_car';
    }
}
