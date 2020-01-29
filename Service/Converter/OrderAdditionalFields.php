<?php

namespace MageSuite\OrderExport\Service\Converter;

class OrderAdditionalFields implements \MageSuite\OrderExport\Service\Converter\OrderAdditionalFieldsInterface
{
    public function getAdditionalFields(\Magento\Sales\Model\Order $order) : array
    {
        return [];
    }
}
