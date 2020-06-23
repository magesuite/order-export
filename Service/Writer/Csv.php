<?php

namespace MageSuite\OrderExport\Service\Writer;

class Csv extends \MageSuite\OrderExport\Service\Writer\Writer implements \MageSuite\OrderExport\Service\Writer\WriterInterface
{
    protected $delimiter = ';';
    protected $enclosure = '"';

    public function writeHeader()
    {
        fputcsv($this->fileHandler, $this->getColumns(), $this->getDelimiter(), $this->getEnclosure());
    }

    public function write($orders)
    {
        $this->writeHeader();

        foreach ($orders as $order) {
            $items = $order['items'];
            foreach ($items as $index => $item) {
                $line = $this->getLine($order, $item, $position);
                fputcsv($this->fileHandler, $line, $this->getDelimiter(), $this->getEnclosure());
            }
        }
    }

    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function getDelimiter()
    {
        return $this->delimiter;
    }

    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    public function getEnclosure()
    {
        return $this->enclosure;
    }

    public function getColumns()
    {
        return [
            'OrderIncrementId',
            'OrderDate',
            'ClientId',
            'BillingTitle',
            'BillingFirstName',
            'BillingLastName',
            'BillingEmail',
            'BillingCompany',
            'BillingStreet',
            'BillingZipCode',
            'BillingCity',
            'BillingCountry',
            'ShippingTitle',
            'ShippingFirstName',
            'ShippingLastName',
            'ShippingEmail',
            'ShippingCompany',
            'ShippingStreet',
            'ShippingZipCode',
            'ShippingCity',
            'ShippingCountry',
            'PaymentType',
            'DeliveryType',
            'ShippingCosts',
            'ErpProductNo',
            'MagentoProductNo',
            'MagentoProductSku',
            'ProductName',
            'Quantity',
            'PricePerItem'
        ];
    }

    public function getLine($order, $item)
    {
        return [
            'OrderIncrementId' => $order['order']['increment_id'],
            'OrderDate' => $order['order']['created_at'],
            'ClientId' => $order['order']['customer_id'],
            'BillingTitle' => $order['order']['billing']['prefix'],
            'BillingFirstName' => $order['order']['billing']['first_name'],
            'BillingLastName' => $order['order']['billing']['last_name'],
            'BillingEmail' => $order['order']['billing']['email'],
            'BillingCompany' => $order['order']['billing']['company'],
            'BillingStreet' => $order['order']['billing']['street'],
            'BillingZipCode' => $order['order']['billing']['postcode'],
            'BillingCity' => $order['order']['billing']['city'],
            'BillingCountry' => $order['order']['billing']['country'],
            'BillingTelephone' => $order['order']['billing']['telephone'],
            'ShippingTitle' => $order['order']['shipping']['prefix'],
            'ShippingFirstName' => $order['order']['shipping']['first_name'],
            'ShippingLastName' => $order['order']['shipping']['last_name'],
            'ShippingEmail' => $order['order']['shipping']['email'],
            'ShippingCompany' => $order['order']['shipping']['company'],
            'ShippingStreet' => $order['order']['shipping']['street'],
            'ShippingZipCode' => $order['order']['shipping']['postcode'],
            'ShippingCity' => $order['order']['shipping']['city'],
            'ShippingCountry' => $order['order']['shipping']['country'],
            'ShippingTelephone' => $order['order']['shipping']['telephone'],
            'PaymentType' => $order['order']['payment_method'],
            'DeliveryType' => $order['order']['shipping_method'],
            'ShippingCosts' => $order['order']['shipping_amount'],
            'ErpProductNo' => $item['product_id'],
            'MagentoProductNo' => $item['product_id'],
            'MagentoProductSku' => $item['sku'],
            'ProductName' => $item['name'],
            'Quantity' => $item['qty_ordered'],
            'PricePerItem' => $item['price_incl_tax']
        ];
    }
}
