<?php

namespace MageSuite\OrderExport\Service\Writer\XmlNodeProcessor;

class ShippingNode implements \MageSuite\OrderExport\Service\Writer\XmlNodeProcessorInterface
{
    public function execute($xml, $order)
    {
        $shippingElement = $xml->createElement('Delivery');

        $shippingMethod = $xml->createElement('DeliveryType', $order['order']['shipping_method']);
        $shippingElement->appendChild($shippingMethod);

        $shippingAmount = $xml->createElement('ShippingCosts', $order['order']['shipping_amount']);
        $shippingElement->appendChild($shippingAmount);

        return $shippingElement;
    }
}
