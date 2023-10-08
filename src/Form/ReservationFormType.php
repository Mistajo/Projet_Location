<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début',
                'input' => 'datetime',
                'widget' => 'choice',
                'attr' => [
                    'id' => 'startDate'
                ],
                'constraints' => [
                    new GreaterThan('now'),
                    new NotBlank(),

                ],
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin',
                'input' => 'datetime',
                'widget' => 'choice',
                'attr' => [
                    'id' => 'endDate'
                ],
                'constraints' => [
                    new NotBlank(),

                ],
            ])
            ->add('totalPrice', MoneyType::class, [
                'label' => 'Total',
                'currency' => 'EUR',
                'attr' => [
                    'class' => 'money',
                    'data-mask' => '000.000.000.000,00',
                    'data-mask-reverse' => 'false',
                    'data-thousands' => ',',
                    'data-decimal' => '.',
                    'data-prefix' => '€ ',
                    'readonly' => true,
                    'id' => 'totalPrice',

                ],
                'constraints' => [
                    new NotBlank(),
                    new Type('numeric'),
                    new Range([
                        'min' => 0,
                        'max' => 9999999,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
