<?php

namespace MageSuite\OrderExport\Service\Converter;

class OrderItemAdditionalFields implements \MageSuite\OrderExport\Service\Converter\OrderItemAdditionalFieldsInterface
{
    public function getAdditionalFields(\Magento\Sales\Api\Data\OrderItemInterface $orderItem, $order) : array
    {
        return [];
    }
}
