<?php

namespace MageSuite\OrderExport\Services\Export\Converter;

use Magento\Sales\Api\Data\OrderItemInterface;

class OrderItem extends \MageSuite\OrderExport\Services\Export\Converter\AbstractOrderItem
{

    /**
     * @param OrderItemInterface $orderItem
     * @return array
     */
    public function toArray(OrderItemInterface $orderItem)
    {
        $data = [
            'order_id' => $orderItem->getOrderId(),
            'sku' => $orderItem->getSku(),
            'product_name' => $orderItem->getName(),
            'quantity' => (int)$orderItem->getQtyOrdered(),
            'price' => number_format($orderItem->getBasePriceInclTax(), 2),
            'product_id' => $orderItem->getProductId()
        ];

        return $data;
    }
}