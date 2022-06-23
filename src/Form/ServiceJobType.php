<?php

namespace App\Form;

use App\Entity\ServiceJob;
use App\Form\Type\JsonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('result')
            ->add('data', JsonType::class)
            ->add('isActive', CheckboxType::class, [
                'required' => false
            ])
            ->add('status')
//            ->add('connection')
//            ->add('command')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ServiceJob::class,
        ]);
    }
}
