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
                'format' => 'dd.MM.yyyy',
                'label' => 'Дата ремонту *'
            ])
            ->add('description', 'textarea', [
                'label' => 'Опис завдання *',
            ])
            ->add('price', 'text', [
                'label' => 'Ціна *'
            ])
            ->add('jobCategory', 'entity', [
                'class' => 'CTOAppBundle:JobCategory',
                'property' => "name",
                'label' => 'Категорія ремонту *'
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    protected function addElements(FormInterface $form, CtoClient $ctoClient = null)
    {
        $form
            ->add('client', 'entity', [
                'data' => $ctoClient,
                'empty_value' => '-- Choose Client --',
                'class' => 'CTOAppBundle:CTOClient',
                'property' => 'fullName',
                'label' => 'Client *',
                ])
            ->add('car', 'entity', [
                'empty_value' => 'select first client',
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
            'data_class' => 'CTO\AppBundle\Entity\CarJob'
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
