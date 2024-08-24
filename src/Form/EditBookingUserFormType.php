<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\MinutesRepas;
use App\Entity\Table;
use App\Entity\TimeMeal;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditBookingUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_booking', null, [
                'widget' => 'single_text'
            ])
           
            ->add('TimeMeal', EntityType::class, [
                'class' => TimeMeal::class,
'choice_label' => 'heure',
            ])
            ->add('minutes', EntityType::class, [
                'class' => MinutesRepas::class,
'choice_label' => 'minute',
            ])
            ->add('guest')
            ->add('typetable', EntityType::class, [
                'class' => Table::class,
'choice_label' => 'name',
'placeholder' => 'Choisissez une table'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
