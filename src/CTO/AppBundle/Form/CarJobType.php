<?php

namespace CTO\AppBundle\Form;

use CTO\AppBundle\Entity\CtoClient;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarJobType extends AbstractType
{
    /** @var EntityManager $em */
    protected $em;

    /** @var CtoClient $client */
    protected $client;

    public function __construct(EntityManager $manager, CtoClient $client = null)
    {
        $this->em = $manager;
        $this->client = $client;
    }

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add("jobDate", "date", [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control date-picker-cto'],
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата ремонту *',
                'data' => new \DateTime('now')
            ])
            ->add("totalMileage", "text", [
                'attr' => ['class' => 'form-control'],
                'label' => 'Пробіг поточний',
            ])
            ->add('carCategories', 'collection', [
                'type' => new CarCategoryType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true
            ])
            ->add('spendingJob', 'collection', [
                'type' => new SpendingJobType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true
            ])
            ->add('usedMaterialsJob', 'collection', [
                'type' => new UsedMaterialsType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true
            ])
            ->add('paidSalaryJob', 'collection', [
                'type' => new PaidSalaryType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true
            ])

//            ->add('client', 'entity', [
//                'class' => 'CTOAppBundle:CtoClient',
//                'property' => 'fullName',
//            ])
//            ->add('car', 'entity', [
//                'class' => 'CTOAppBundle:ClientCar',
//                'property' => 'carModel',
//            ])

//            ->add('description', 'textarea', [
//                'label' => 'Опис завдання *',
//                'attr' => [
//                    'class' => 'form-control',
//                    'rows' => 6
//                ],
//            ])
//            ->add('price', 'text', [
//                'label' => 'Ціна (грн.)*',
//                'attr' => ['class' => 'form-control'],
//            ])
//            ->add('jobCategory', 'entity', [
//                'class' => 'CTOAppBundle:JobCategory',
//                'property' => "name",
//                'label' => 'Категорія ремонту *',
//                'attr'  => [
//                    'class'         => 'selectpicker',
//                    'data-width'    => "100%",
////                    'data-size'     => "15",
////                    'data-live-search' => true
//                ],
//            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    protected function addElements(FormInterface $form, CtoClient $ctoClient = null)
    {
        $form
            ->add('client', 'entity', [
                'data' => $ctoClient,
                'empty_value' => '-- Виберіть клієнта --',
                'class' => 'CTOAppBundle:CtoClient',
                'property' => 'fullName',
                'label' => 'Клієнт *',
                'attr'  => [
                    'class'         => 'selectpicker',
                    'data-width'    => "100%",
//                    'data-size'     => "15",
//                    'data-live-search' => true
                ],
                ])
            ->add('car', 'entity', [
                'empty_value' => '-- Спочатку виберіть клієнта --',
                'label' => 'Автомобілі клієнта',
                'attr'  => [
                    'class'         => 'selectpicker',
                    'data-width'    => "100%",
//                    'data-size'     => "15",
//                    'data-live-search' => true
                ],
                'class' => 'CTOAppBundle:ClientCar',
                'property' => 'carModel',
                'choices' => $ctoClient ? $ctoClient->getCars() : []
            ])
        ;
    }

    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $client = $this->em->getRepository('CTOAppBundle:CtoClient')->find($data['client']);
        $this->addElements($form, $client ? $client : $this->client);
    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $this->addElements($form, $this->client);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CTO\AppBundle\Entity\CarJob',
            'cascade_validation' => true
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "car_job";
    }
}
