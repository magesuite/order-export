<?php

namespace MageSuite\OrderExport\Services\Export\Converter;

class OrderCollection extends \MageSuite\OrderExport\Services\Export\Converter\AbstractOrderCollection
{
    /**
     * @var \MageSuite\OrderExport\Services\Export\Converter\Order
     */
    protected $orderConverter;

    public function __construct(
        \MageSuite\OrderExport\Services\Export\Converter\Order $orderConverter
    )
    {
        $this->orderConverter = $orderConverter;
    }

    /**
     * @param \Magento\Sales\Model\Order $orders
     * @return array
     */
    public function toArray($orders)
    {
        $result = [];
        foreach ($orders as $order) {
            $result[] = $this->orderConverter->toArray($order);
        }

        return $result;
    }

}