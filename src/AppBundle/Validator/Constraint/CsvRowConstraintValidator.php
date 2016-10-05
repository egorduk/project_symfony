<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CsvRowConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ( !(($value->getCost() > 5 && $value->getCost() < 1000) && $value->getStock() > 10) ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}