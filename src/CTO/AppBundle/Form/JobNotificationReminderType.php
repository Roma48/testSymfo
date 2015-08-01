<?php

namespace CTO\AppBundle\Form;

use CTO\AppBundle\Entity\CarJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobNotificationReminderType extends AbstractType
{
    /** @var  CarJob */
    private $carJob;

    public function __construct(CarJob $carJob)
    {
        $this->carJob = $carJob;
    }

    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('carJobCategory', 'entity', [
                'class' => 'CTO\AppBundle\Entity\CarCategory',
                'choices' => $this->carJob->getCarCategories()
            ])
            ->add('description', 'textarea')
            ->add('whenSend', "datetime", [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datetime-picker-cto'],
                'format' => 'dd.MM.yyyy HH:mm',
                'label' => 'Дата нагадування *',
                'data' => new \DateTime('now')
            ])
            ->add('autoSending')
            ->add('adminCopy')
            ->add('Ok', 'submit')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CTO\AppBundle\Entity\Notification'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'job_reminder';
    }
}
