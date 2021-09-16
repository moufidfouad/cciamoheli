<?php

namespace App\Validator;

use App\Tools\Constants;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsSortieValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsSortie) {
            throw new UnexpectedTypeException($constraint, IsSortie::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if(!in_array($value,[
            Constants::SORTIE_CHOMAGE,
            Constants::SORTIE_DEMISSION,
            Constants::SORTIE_LICENCIEMENT,
            Constants::SORTIE_MISAPIED
        ])){
            $this->context->buildViolation($constraint->message)
                //->setParameter('{{ value }}', $value)
                ->atPath('forme')
                ->addViolation();
        }
    }
}
