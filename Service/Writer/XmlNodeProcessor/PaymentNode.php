<?php

namespace MageSuite\OrderExport\Service\Writer\XmlNodeProcessor;

class PaymentNode implements \MageSuite\OrderExport\Service\Writer\XmlNodeProcessorInterface
{
    public function execute($xml, $order)
    {
        $paymentElement = $xml->createElement('Payment');

        $paymentType = $xml->createElement('PaymentType', $order['order']['payment_method']);
        $paymentElement->appendChild($paymentType);

        return $paymentElement;
    }
}
