<?php

namespace MageSuite\OrderExport\Service\Converter;

interface OrderAdditionalFieldsInterface
{
    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    public function getAdditionalFields(\Magento\Sales\Model\Order $order): array;
}
