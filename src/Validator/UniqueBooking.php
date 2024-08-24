<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class UniqueBooking extends Constraint
{
    public $message = 'Vous avez déjà une réservation pour ce créneau horaire.';

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}

