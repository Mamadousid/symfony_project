<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Booking;

class UniqueBookingValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        // Assuming $value is an instance of Booking
        $existingBooking = $this->entityManager->getRepository(Booking::class)->findOneBy([
            'email' => $value->getEmail(),
            'date_booking' => $value->getDateBooking(),
            'TimeMeal' => $value->getTimeMeal(),
            'minutes' => $value->getMinutes(),
        ]);

        if ($existingBooking && $existingBooking->getId() !== $value->getId()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('date_booking')
                ->addViolation();
        }
    }
}
