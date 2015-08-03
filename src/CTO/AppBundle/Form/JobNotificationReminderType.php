<?php

namespace CTO\AppBundle\Form;

use CTO\AppBundle\Entity\CarJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'label' => 'Категорія ремонту',
                'attr' => ['class' => 'form-control'],
                'choices' => $this->carJob->getCarCategories()
            ])
            ->add('description', 'textarea', [
                'attr' => ['class' => 'form-control notification-description'],
                'label' => 'Повідомлення *',
                'constraints' => [
                    new NotBlank(['message' => "Це поле обов'язкове"])
                ]
            ])
            ->add('whenSend', "datetime", [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control datetime-picker-cto'],
                'format' => 'dd.MM.yyyy HH:mm',
                'label' => 'Дата нагадування *',
                'data' => new \DateTime('now'),
                'constraints' => [
                    new NotBlank(['message' => "Це поле обов'язкове"])
                ]
            ])
            ->add('autoSending', 'checkbox' ,[
                'attr' => ['class' => ''],
                'label' => 'Відправити автоматично',
                'required' => false
            ])
            ->add('adminCopy', 'checkbox', [
                'attr' => ['class' => ''],
                'label' => 'Відправити копію адміністратору',
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
