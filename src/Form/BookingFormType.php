<?php
namespace App\Form;

use App\Entity\Table;
use App\Entity\Booking;
use App\Entity\TimeMeal;
use App\Entity\MinutesRepas;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', TextType::class)
            ->add('phone', TelType::class)
            ->add('date_booking', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => true,
                'input' => 'datetime_immutable',
                'required' => true,
                'attr' => [
                    'min' => (new \DateTimeImmutable())->format('Y-m-d'), // Date actuelle au format 'YYYY-MM-DD'
                ],
            ])
            ->add('guest',IntegerType::class)
            ->add('message', TextareaType::class)
            ->add('TimeMeal', EntityType::class, [
                'class' => TimeMeal::class,
                'choice_label' => 'heure',
            ])
            ->add('minutes', EntityType::class, [
                'class' => MinutesRepas::class,
                'choice_label' => 'minute',
            ])
            ->add('typetable', EntityType::class, [
                'class' => Table::class,
                'choice_label' => 'name',
                // Les options seront définies dynamiquement
                'placeholder' => 'Choisissez une table',
            ]);

        // Ajouter un écouteur d'événement pour filtrer les types de table disponibles
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $booking = $event->getData();

            if (!$booking || !$booking->getGuest()) {
                return;
            }

            // Filtrer les types de table en fonction du nombre d'invités
            $form->add('typetable', EntityType::class, [
                'class' => Table::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($booking) {
                    return $er->createQueryBuilder('t')
                        ->where('t.places >= :guestCount')
                        ->setParameter('guestCount', $booking->getGuest());
                },
                'placeholder' => 'Choisissez une table',
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}