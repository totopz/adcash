<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`product`")
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=false, options={"unsigned"=true})
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     */
    private $discountMinimumQuantity;

    /**
     * @var string
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned"=true})
     */
    private $discountPercent;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getDiscountMinimumQuantity()
    {
        return $this->discountMinimumQuantity;
    }

    /**
     * @param string $discountMinimumQuantity
     */
    public function setDiscountMinimumQuantity($discountMinimumQuantity)
    {
        $this->discountMinimumQuantity = $discountMinimumQuantity;
    }

    /**
     * @return string
     */
    public function getDiscountPercent()
    {
        return $this->discountPercent;
    }

    /**
     * @param string $discountPercent
     */
    public function setDiscountPercent($discountPercent)
    {
        $this->discountPercent = $discountPercent;
    }
}