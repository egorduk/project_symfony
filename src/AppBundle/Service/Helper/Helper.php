<?php

namespace AppBundle\Service\Helper;

use AppBundle\Entity\Product;
use AppBundle\Validator\Constraint\CsvRowConstraint;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Helper
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Creates product entity from csv row data
     *
     * @return Product
     */
    public function getProductEntityFromCsvRow($rowData)
    {
        $product = new Product();

        $code = isset($rowData[0]) ? $rowData[0] : "";
        $product->setCode($code);

        $name = isset($rowData[1]) ? $rowData[1] : "";
        $product->setName($name);

        $description = isset($rowData[2]) ? $rowData[2] : "";
        $product->setDescription($description);

        $stock = isset($rowData[3]) ? $rowData[3] : 0;
        $product->setStock($stock);

        $cost = isset($rowData[4]) ? $rowData[4] : 0;
        $product->setCost($cost);

        $discount = isset($rowData[5]) && ($rowData[5] == 'yes') ? new \DateTime() : null;
        $product->setDiscontinued($discount);

        $errors = $this->validator->validate($product);
        $countErrors = count($errors);

        return (!$countErrors) ? $product : null;
    }
}