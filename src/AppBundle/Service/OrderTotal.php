<?php

namespace AppBundle\Service;

use AppBundle\Entity\Order;

class OrderTotal
{
    /**
     * Calculate order total amount
     *
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order)
    {
        $product = $order->getProduct();

        $total = $order->getQuantity() * $product->getPrice();

        $discountMinimumQuantity = $product->getDiscountMinimumQuantity();

        if ($discountMinimumQuantity !== null && $order->getQuantity() >= $discountMinimumQuantity) {
            $total -= $total * $product->getDiscountPercent() / 100;
        }

        return $total;
    }
}
