<?php

namespace MageSuite\OrderExport\Service\Writer\XmlNodeProcessor;

class HeadNode implements \MageSuite\OrderExport\Service\Writer\XmlNodeProcessorInterface
{
    public function execute($xml, $order)
    {
        $headElement = $xml->createElement('Head');

        $mode = $xml->createElement('Mode', 'TEST');
        $headElement->appendChild($mode);

        $merchant = $xml->createElement('Merchant');

        $erpMerchantId = $xml->createElement('ErpMerchantId', '999999');
        $merchant->appendChild($erpMerchantId);

        $magentoStoreId = $xml->createElement('MagentoStoreId', $order['order']['store_id']);
        $merchant->appendChild($magentoStoreId);

        $headElement->appendChild($merchant);

        $client = $xml->createElement('Client');

        $magentoClientId = $xml->createElement('MagentoClientId', $order['order']['customer_id']);
        $client->appendChild($magentoClientId);

        $headElement->appendChild($client);

        $orderDate = $xml->createElement('OrderDate', $order['order']['created_at']);
        $headElement->appendChild($orderDate);

        $magentoOrderNo = $xml->createElement('MagentoOrderNo', $order['order']['increment_id']);
        $headElement->appendChild($magentoOrderNo);

        return $headElement;
    }
}
