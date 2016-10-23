<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraint as AppAssert;

/**
 * Product
 *
 * @ORM\Table(name="tblProductData", uniqueConstraints={@ORM\UniqueConstraint(name="strProductCode", columns={"strProductCode"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 *
 * @AppAssert\CsvRowConstraint(
 *     minStock = 10,
 *     maxCost = 1000,
 *     minCost = 5
 * )
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
     */
    private $stock;

    /**
     * @var float
     *
     * @ORM\Column(name="strProductCost", type="float", precision=10, scale=0, nullable=false, options={"unsigned"=true})
     *
     */
    private $cost;



    /**
     * Set name
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setName($productName)
    {
        $this->name = $productName;

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
     * @param string $productDesc
     *
     * @return Product
     */
    public function setDescription($productDesc)
    {
        $this->description = $productDesc;

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
     * @param string $productCode
     *
     * @return Product
     */
    public function setCode($productCode)
    {
        $this->code = $productCode;

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
     * @param \DateTime $productAdded
     *
     * @return Product
     */
    public function setAdded($productAdded)
    {
        $this->added = $productAdded;

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
     * @param \DateTime $productDiscontinued
     *
     * @return Product
     */
    public function setDiscontinued($productDiscontinued)
    {
        $this->discontinued = $productDiscontinued;

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
     * @param integer $productStock
     *
     * @return Product
     */
    public function setStock($productStock)
    {
        $this->stock = $productStock;

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
     * @param float $productCost
     *
     * @return Product
     */
    public function setCost($productCost)
    {
        $this->cost = $productCost;

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
