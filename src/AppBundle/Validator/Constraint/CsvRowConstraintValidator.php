<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CsvRowConstraintValidator extends ConstraintValidator
{
    public function isValid($value, Constraint $constraint)
    {
        if (isset($value[3]) && isset($value[4]) && $value[3] != "" && $value[4] != "") {
            if ($value[3] > 10 && $value[4] > 5 && $value[4] < 1000) {
                return true;
            }
        }

        return false;
    }

    public function validate($value, Constraint $constraint)
    {
    }
}