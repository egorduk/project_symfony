<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CsvRowConstraint extends Constraint
{
    public $message = 'The csv row has invalid format';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}