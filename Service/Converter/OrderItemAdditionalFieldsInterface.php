<?php

namespace MageSuite\OrderExport\Service\Converter;

interface OrderItemAdditionalFieldsInterface
{
    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface $orderItem
     * @param array $order
     * @return array
     */
    public function getAdditionalFields(\Magento\Sales\Api\Data\OrderItemInterface $orderItem, $order): array;
}
