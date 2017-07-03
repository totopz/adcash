<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Order;
use AppBundle\Service\OrderTotal;

class HelpersExtension extends \Twig_Extension
{
    /**
     * @var OrderTotal
     */
    private $orderTotal;

    public function __construct(OrderTotal $orderTotal)
    {
        $this->orderTotal = $orderTotal;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('calculateOrderTotal', array($this, 'calculateOrderTotal')),
        );
    }

    /**
     * @param Order $order
     * @return string
     */
    public function calculateOrderTotal(Order $order)
    {
        return '$' . number_format($this->orderTotal->calculate($order), 2);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_helpers';
    }
}
