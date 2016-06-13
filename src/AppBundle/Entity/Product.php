<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="tblProductData")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="intProductDataId", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="strProductName", type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(name="strProductCode", type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(name="dtmAdded", type="datetime", nullable=true)
     */
    private $added;

    /**
     * @ORM\Column(name="dtmDiscontinued", type="datetime", nullable=true)
     */
    private $discontinued;

    /**
     * @ORM\Column(name="stmTimestamp", type="timestamp", options={"default":"CURRENT_TIMESTAMP"})
     */
    //private $stmTimestamp;

    /**
     * @ORM\Column(name="strProductStock", type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(name="strProductCost", type="float")
     */
    private $cost;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @param mixed $added
     */
    public function setAdded($added)
    {
        $this->added = $added;
    }

    /**
     * @return mixed
     */
    public function getDiscontinued()
    {
        return $this->discontinued;
    }

    /**
     * @param mixed $discontinued
     */
    public function setDiscontinued($discontinued)
    {
        $this->discontinued = $discontinued;
    }

    /**
     * @return mixed
     */
    /*public function getStmTimestamp()
    {
        return $this->stmTimestamp;
    }

    /**
     * @param mixed $stmTimestamp
     */
    /*public function setStmTimestamp($stmTimestamp)
    {
        $this->stmTimestamp = $stmTimestamp;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    }
}
