<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraint as AppAssert;
//     * @AppAssert\CsvRowConstraint

/**
 * Product
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="intProductDataId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductName", type="string", length=50, nullable=false)
     *
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductDesc", type="string", length=255, nullable=false)
     *
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="strProductCode", type="string", length=10, nullable=false)
     *
     * @Assert\NotBlank
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $added;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $discontinued;

    /**
     * @var integer
     *
     * @ORM\Column(name="strProductStock", type="integer", nullable=false, options={"unsigned"=true})
     *
     * @AppAssert\CsvRowConstraint
     */
    private $stock;

    /**
     * @var float
     *
     * @ORM\Column(name="strProductCost", type="float", precision=10, scale=0, nullable=false, options={"unsigned"=true})
     *
     * @AppAssert\CsvRowConstraint
     */
    private $cost;



    /**
     * Set name
     *
     * @param string $strproductname
     *
     * @return Product
     */
    public function setName($strproductname)
    {
        $this->name = $strproductname;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $strproductdesc
     *
     * @return Product
     */
    public function setDescription($strproductdesc)
    {
        $this->description = $strproductdesc;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set code
     *
     * @param string $strproductcode
     *
     * @return Product
     */
    public function setCode($strproductcode)
    {
        $this->code = $strproductcode;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set added
     *
     * @param \DateTime $dtmadded
     *
     * @return Product
     */
    public function setAdded($dtmadded)
    {
        $this->added = $dtmadded;

        return $this;
    }

    /**
     * Get added
     *
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set discontinued
     *
     * @param \DateTime $dtmdiscontinued
     *
     * @return Product
     */
    public function setDiscontinued($dtmdiscontinued)
    {
        $this->discontinued = $dtmdiscontinued;

        return $this;
    }

    /**
     * Get discontinued
     *
     * @return \DateTime
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * Set stock
     *
     * @param integer $strproductstock
     *
     * @return Product
     */
    public function setStock($strproductstock)
    {
        $this->stock = $strproductstock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set cost
     *
     * @param float $strproductcost
     *
     * @return Product
     */
    public function setCost($strproductcost)
    {
        $this->cost = $strproductcost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
