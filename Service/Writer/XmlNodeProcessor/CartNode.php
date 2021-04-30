<?php

namespace MageSuite\OrderExport\Service\Writer\XmlNodeProcessor;

class CartNode implements \MageSuite\OrderExport\Service\Writer\XmlNodeProcessorInterface
{
    public function execute($xml, $order)
    {
        $cartElement = $xml->createElement('Cart');

        $position = 1;

        foreach ($order['items'] as $item) {
            $itemNode = $xml->createElement('Item');

            foreach ($this->getItemFieldsMap($position, $item) as $name => $value) {
                $element = $xml->createElement($name);
                $element->appendChild($xml->createTextNode($value));
                $itemNode->appendChild($element);
            }

            $cartElement->appendChild($itemNode);
            $position++;
        }

        return $cartElement;
    }

    protected function getItemFieldsMap($position, $item)
    {
        return [
            'PosNo' => $position,
            'ErpProductNo' => $item['erp_product_id'],
            'MagentoProductNo' => $item['product_id'],
            'MagentoProductSku' => $item['sku'],
            'ProductName' => $item['product_name'],
            'Quantity' => $item['quantity'],
            'PricePerItem' => $item['price'],
            'TaxPerItem' => $item['tax_percent']
        ];
    }
}
