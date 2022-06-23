<?php

namespace App\Form;

use App\Entity\ServiceJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('result')
            ->add('data')
            ->add('isActive')
            ->add('status')
            ->add('connection')
            ->add('command')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceJob::class,
        ]);
    }
}
