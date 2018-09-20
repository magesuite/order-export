<?php

namespace MageSuite\OrderExport\Services\Export\Converter;


abstract class AbstractOrderItem
{
    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface $orderItem
     * @return array
     */
    abstract public function toArray(\Magento\Sales\Api\Data\OrderItemInterface $orderItem);

}