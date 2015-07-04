<?php

namespace CTO\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobDescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('description', 'textarea', [
                'label' => 'Опис завдання *',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 6
                ],
            ])
            ->add('price', 'text', [
                'label' => 'Ціна (грн.)*',
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CTO\AppBundle\Entity\CategoryJobDescription'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'job_description';
    }
}