<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderRepository;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderRepositoryTest extends KernelTestCase
{
    /** @var EntityManager */
    private $em;

    /** @var OrderRepository */
    private $orderRepository;

    /** @var array */
    private $createdOrders = [];

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->orderRepository = $this->em->getRepository('AppBundle:Order');
    }

    protected function tearDown()
    {
        foreach ($this->createdOrders as $order) {
            $this->em->remove($order);
            $this->em->flush();
        }

        parent::tearDown();
    }

    public function testSearchValid()
    {
        $order1 = $this->createOrder();
        $order2 = $this->createOrder();

        /** @var OrderRepository $repository */
        $repository = $this->orderRepository;

        $query = $repository->search();

        $result = $query->getResult();

        $orderIds = [];

        /** @var Order $order */
        foreach ($result as $order) {
            $orderIds[] = $order->getId();
        }

        $this->assertContains($order1->getId(), $orderIds);
        $this->assertContains($order2->getId(), $orderIds);
    }

    public function testSearchValidPeriod()
    {
        $olderData = new \DateTime();
        $olderData->sub(new \DateInterval('P10D'));

        $order1 = $this->createOrder($olderData);

        $newerData = new \DateTime();
        $newerData->sub(new \DateInterval('P5D'));

        $order2 = $this->createOrder($newerData);

        /** @var OrderRepository $repository */
        $repository = $this->orderRepository;

        $query = $repository->search(OrderRepository::PERIOD_LAST_7_DAYS);

        $result = $query->getResult();

        $orderIds = [];

        /** @var Order $order */
        foreach ($result as $order) {
            $orderIds[] = $order->getId();
        }

        $this->assertNotContains($order1->getId(), $orderIds);
        $this->assertContains($order2->getId(), $orderIds);
    }

    public function testSearchInvalidPeriod()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Invalid period passed');

        $this->orderRepository->search(rand(1, 10000));
    }

    public function testSearchInvalidTerm()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'Invalid term passed');

        $this->orderRepository->search(null, array('test'));
    }

    /**
     * @param \DateTime|null $createdAt
     * @return Order
     */
    private function createOrder(\DateTime $createdAt = null)
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneBy([]);

        /** @var Product $product */
        $product = $this->em->getRepository('AppBundle:Product')->findOneBy([]);

        if ($createdAt === null) {
            $createdAt = new \DateTime();
        }

        $order = new Order();
        $order->setUser($user);
        $order->setProduct($product);
        $order->setQuantity(rand(1, 100));
        $order->setCreatedAt($createdAt);

        $this->em->persist($order);
        $this->em->flush();

        $this->createdOrders[] = $order;

        return $order;
    }
}