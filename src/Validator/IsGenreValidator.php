<?php

namespace App\Validator;

use App\Tools\Constants;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsGenreValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsGenre) {
            throw new UnexpectedTypeException($constraint, IsGenre::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if(!in_array($value,array_values(Constants::$GENRE_CHOICES))){
            $this->context->buildViolation($constraint->message)
                //->setParameter('{{ value }}', $value)
                ->atPath('genre')
                ->addViolation();
        }
    }
}
