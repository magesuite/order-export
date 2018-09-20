<?php

namespace MageSuite\OrderExport\Services\Export\Converter;

abstract class AbstractOrder
{

    /**
     * @var \Magento\Shipping\Model\Config\Source\Allmethods
     */
    protected $allmethods;

    public function __construct(
        \Magento\Shipping\Model\Config\Source\Allmethods $allmethods
    )
    {
        $this->allmethods = $allmethods;
    }

    abstract public function toArray(\Magento\Sales\Model\Order $order);

    protected function getShipmentMethodTitle(\Magento\Sales\Model\Order $order)
    {
        $shippingMethod = $order->getShippingMethod();
        if (empty($shippingMethod)) {
            return '';
        }

        $allMethods = $this->allmethods->toOptionArray();
        list($carrierCode, $carrierMethod) = explode('_', $shippingMethod, 2);
        if (array_key_exists($carrierMethod, $allMethods)) {
            return $allMethods[$carrierMethod]['label'];
        }

        if (!array_key_exists($carrierCode, $allMethods)) {
            return '';
        }
        foreach ($allMethods[$carrierCode]['value'] as $method) {
            if ($method['value'] != $shippingMethod) {
                continue;
            }

            return $this->removeCarrierCodeFromShippingTitle($method['label']);
        }

        return '';
    }

    protected function removeCarrierCodeFromShippingTitle($shippingMethodTitle)
    {
        return substr(strstr($shippingMethodTitle, ' '), 1);
    }
}