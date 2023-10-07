<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'constraints' => [
                    new GreaterThan('today UTC'),
                    new NotBlank(),
                    new Date(),
                    new DateTime()
                ],
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new Date(),
                    new GreaterThan('startDate'),
                    new DateTime(),
                ],
            ])
            ->add('totalPrice', MoneyType::class, [
                'label' => 'Total',
                'currency' => 'EUR',
                'divisor' => 100,
                'grouping' => true,
                'attr' => ['class' => 'money'],
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
