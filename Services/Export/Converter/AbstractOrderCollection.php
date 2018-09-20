<?php

namespace MageSuite\OrderExport\Services\Export\Converter;

abstract class AbstractOrderCollection
{

    /**
     * @param \Magento\Sales\Model\Order $orders
     * @return array
     */
    abstract public function toArray($orders);

}