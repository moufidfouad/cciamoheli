<?php

namespace App\Validator;

use App\Tools\Constants;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsReposValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsRepos) {
            throw new UnexpectedTypeException($constraint, IsRepos::class);
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if(!in_array($value,array_values(Constants::$REPOS_CHOICES))){
            $this->context->buildViolation($constraint->message)
                //->setParameter('{{ value }}', $value)
                ->atPath('forme')
                ->addViolation();
        }
    }
}
