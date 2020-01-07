<?php

namespace MageSuite\OrderExport\Service\Converter;

class OrderItem implements \MageSuite\OrderExport\Service\Converter\OrderItemInterface
{
    public function convert(\Magento\Sales\Api\Data\OrderItemInterface $orderItem): array
    {
        $result = $orderItem->toArray();

        $this->addAdditionalFieldsToOrderItem($result, $orderItem);

        return $result;
    }

    public function addAdditionalFieldsToOrderItem(&$result, $orderItem)
    {
        $additionalFields = [
            'product_name' => $orderItem->getName(),
            'quantity' => (float)$orderItem->getQtyOrdered(),
        ];

        $result = array_merge($result, $additionalFields);
    }
}
