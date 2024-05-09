<?php

namespace App\Form;

use App\Entity\CurrencyExchangeRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyExchangeRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('fullDate', null, [
                'widget' => 'single_text',
            ])
            ->add('value')
            ->add('week')
            ->add('month')
            ->add('quarter')
            ->add('year')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CurrencyExchangeRate::class,
        ]);
    }
}
