<?php

namespace CTO\AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('client', EntityType::class, [
                'label' => 'Клієнт: *',
                'choice_label' => 'fullName',
                'attr' => [
                    "class" => "form-control selectpicker",
                    "data-live-search" => "true"
                ],
                'required' => false,
                'class' => 'CTO\AppBundle\Entity\CtoClient'
            ])
            ->add('car', EntityType::class, [
                'label' => 'Автомобіль: *',
                'choice_label' => 'carModel',
                'attr' => [
                    "class" => "form-control selectpicker",
                    "data-live-search" => "true"
                ],
                'required' => false,
                'class' => 'CTO\AppBundle\Entity\ClientCar'
            ])
            ->add('workplace', EntityType::class, [
                'label' => 'Робоче місце: *',
                'choice_label' => 'title',
                'attr' => [
                    "class" => "form-control selectpicker",
                    "data-live-search" => "true"
                ],
                'class' => 'CTO\AppBundle\Entity\Workplace'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Опис: *',
                'attr' => ["class" => "form-control"],
                'required' => false
            ])
            ->add('startAt', TextType::class, [
                'label' => 'Початок: *',
                'attr' => ["class" => "form-control event-date-picker"],
                'required' => false
            ])
            ->add('endAt', TextType::class, [
                'label' => 'Кінець: *',
                'attr' => ["class" => "form-control event-date-picker"],
                'required' => false
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CTO\AppBundle\Entity\Event'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'event';
    }
}