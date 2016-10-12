<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CsvRowConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if ( !(($value->getCost() > $constraint->minCost && $value->getCost() < $constraint->maxCost) && $value->getStock() > $constraint->minStock) ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}