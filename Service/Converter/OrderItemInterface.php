<?php

namespace MageSuite\OrderExport\Service\Converter;

interface OrderItemInterface
{
    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface $orderItem
     * @return array
     */
    public function convert(\Magento\Sales\Api\Data\OrderItemInterface $orderItem): array;
}
