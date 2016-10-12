<?php

namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CsvRowConstraint extends Constraint
{
    public $message = 'The csv row has invalid format';
    public $minCost = 0;
    public $maxCost = 0;
    public $minStock = 0;

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}