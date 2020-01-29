<?php

namespace MageSuite\OrderExport\Service\Converter;

interface OrderInterface
{
    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    public function convert(\Magento\Sales\Model\Order $order): array;
}
