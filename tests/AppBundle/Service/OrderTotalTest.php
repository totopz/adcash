<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Service\OrderTotal;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderTotalTest extends KernelTestCase
{
    /** @var  OrderTotal */
    private $orderTotal;

    /** @var EntityManager */
    private $em;

    protected function setUp()
    {
        self::bootKernel();

        $container = static::$kernel->getContainer();

        $this->orderTotal = $container->get('app_order_total');
        $this->em = $container->get('doctrine')->getManager();
    }

    public function testCalculateCocaCola()
    {
        $product = $this->getProduct('Coca Cola');

        $quantity = rand(1, 100);

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setProduct($product);
        $order->setQuantity($quantity);

        $expectedResult = $product->getPrice() * $quantity;

        $result = $this->orderTotal->calculate($order);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculatePepsiColaWithoutDiscount()
    {
        $product = $this->getProduct('Pepsi Cola');

        $quantity = rand(1, 2);

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setProduct($product);
        $order->setQuantity($quantity);

        $expectedResult = $product->getPrice() * $quantity;

        $result = $this->orderTotal->calculate($order);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCalculatePepsiColaWithDiscount()
    {
        $product = $this->getProduct('Pepsi Cola');

        $quantity = rand(3, 100);

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setProduct($product);
        $order->setQuantity($quantity);

        $total = $product->getPrice() * $quantity;

        $expectedResult = $total - $total * $product->getDiscountPercent() / 100;

        $result = $this->orderTotal->calculate($order);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return User
     */
    private function getUser()
    {
        $users = $this->em->getRepository('AppBundle:User')->findAll();

        return current($users);
    }

    /**
     * @param string $name
     * @return Product
     */
    private function getProduct($name)
    {
        /** @var Product $product */
        $product = $this->em->getRepository('AppBundle:Product')->findOneBy(['name' => $name]);

        return $product;
    }
}