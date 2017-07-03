<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;

class LoadProductData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setName('Coca Cola');
        $product->setPrice(1.8);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Pepsi Cola');
        $product->setPrice(1.6);
        $product->setDiscountMinimumQuantity(3);
        $product->setDiscountPercent(20);
        $manager->persist($product);

        $product = new Product();
        $product->setName('Sprite');
        $product->setPrice(1.2);
        $manager->persist($product);

        $manager->flush();
    }
}