<?php

namespace MageSuite\OrderExport\Services\File\Adapter;

use MageSuite\OrderExport\Services\File\Writer;

class XMLWriter implements Writer
{

    protected $data;

    protected $fileHandler;

    /**
     * @param string $filePath
     */
    public function openFile($filePath)
    {
        $this->checkPath($filePath);
        $this->fileHandler = fopen($filePath, "w");
    }

    public function closeFile()
    {
        fclose($this->fileHandler);
    }

    public function write($data)
    {
        $strategy = $data['strategy'];

        unset($data['strategy']);

        if($strategy == 'grouped') {
            $this->writeGrouped($data);
        } else {
            $this->writeSeparated($data);
        }
    }

    public function writeSeparated($data)
    {
        $this->data = $data;
        $xml = new \DOMDocument();

        $orderElem = $xml->createElement('Order');

        $headNode = $this->getHeadNode();
        $paymentNode = $this->getPaymentNode();
        $deliveryNode = $this->getDeliveryNode();
        $addressNode = $this->getAddressNode();
        $cartNode = $this->getCartNode();

        $orderElem->appendChild($xml->importNode($headNode->documentElement->cloneNode(true), true));
        $orderElem->appendChild($xml->importNode($paymentNode->documentElement->cloneNode(true), true));
        $orderElem->appendChild($xml->importNode($deliveryNode->documentElement->cloneNode(true), true));
        $orderElem->appendChild($xml->importNode($addressNode->documentElement->cloneNode(true), true));
        $orderElem->appendChild($xml->importNode($cartNode->documentElement->cloneNode(true), true));

        $xml->appendChild($orderElem);

        $xml->formatOutput = true;
        fputs($this->fileHandler,$xml->saveXML());
    }

    public function writeGrouped($data)
    {
        $xml = new \DOMDocument();

        $ordersElem = $xml->createElement('Orders');

        foreach ($data as $order) {

            $this->data = $order;

            $orderElem = $xml->createElement('Order');

            $headNode = $this->getHeadNode();
            $paymentNode = $this->getPaymentNode();
            $deliveryNode = $this->getDeliveryNode();
            $addressNode = $this->getAddressNode();
            $cartNode = $this->getCartNode();

            $orderElem->appendChild($xml->importNode($headNode->documentElement->cloneNode(true), true));
            $orderElem->appendChild($xml->importNode($paymentNode->documentElement->cloneNode(true), true));
            $orderElem->appendChild($xml->importNode($deliveryNode->documentElement->cloneNode(true), true));
            $orderElem->appendChild($xml->importNode($addressNode->documentElement->cloneNode(true), true));
            $orderElem->appendChild($xml->importNode($cartNode->documentElement->cloneNode(true), true));

            $ordersElem->appendChild($orderElem);
        }

        $xml->appendChild($ordersElem);

        $xml->formatOutput = true;
        fputs($this->fileHandler,$xml->saveXML());
    }

    /**
     * @param string $filePath
     */
    private function checkPath($filePath)
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }


    public function getHeadNode()
    {
        $data = $this->data;

        $dom = new \DOMDocument();

        $headElement = $dom->createElement('Head');

        $mode = $dom->createElement('Mode', 'TEST');

        $merchant = $dom->createElement('Merchant');
        $erpMerchantId = $dom->createElement('ErpMerchantId', '999999');
        $magentoStoreId = $dom->createElement('MagentoStoreId', $data['order']['store_id']);
        $merchant->appendChild($erpMerchantId);
        $merchant->appendChild($magentoStoreId);

        $client = $dom->createElement('Client');
        $magentoClientId = $dom->createElement('MagentoClientId', $data['order']['customer_id']);
        $client->appendChild($magentoClientId);

        $orderDate = $dom->createElement('OrderDate', $data['order']['creation_date']);

        $magentoOrderNo = $dom->createElement('MagentoOrderNo', $data['increment_id']);

        $headElement->appendChild($mode);
        $headElement->appendChild($merchant);
        $headElement->appendChild($client);
        $headElement->appendChild($orderDate);
        $headElement->appendChild($magentoOrderNo);

        $dom->appendChild($headElement);
        $dom->formatOutput = true;

        return $dom;
    }

    public function getPaymentNode()
    {
        $data = $this->data;
        $dom = new \DOMDocument();

        $paymentElement = $dom->createElement('Payment');

        $paymentType = $dom->createElement('PaymentType', $data['order']['payment_method']);

        $paymentElement->appendChild($paymentType);

        $dom->appendChild($paymentElement);
        $dom->formatOutput = true;

        return $dom;
    }

    public function getDeliveryNode()
    {
        $data = $this->data;

        $dom = new \DOMDocument();

        $deliveryElement = $dom->createElement('Delivery');

        $paymentType = $dom->createElement('DeliveryType', $data['order']['shipping_method']);
        $shippingCosts = $dom->createElement('ShippingCosts', $data['order']['shipping_amount']);

        $deliveryElement->appendChild($paymentType);
        $deliveryElement->appendChild($shippingCosts);

        $dom->appendChild($deliveryElement);
        $dom->formatOutput = true;

        return $dom;
    }

    public function getAddressNode()
    {
        $data = $this->data;

        $dom = new \DOMDocument();

        $addressElement = $dom->createElement('Address');

        $deliveryAddress = $dom->createElement('DeliveryAddress');

        $title = $dom->createElement('Title', $data['order']['shipping_prefix']);
        $firstName = $dom->createElement('FirstName', $data['order']['shipping_first_name']);
        $lastName = $dom->createElement('LastName', $data['order']['shipping_first_name']);
        $company = $dom->createElement('Company', $data['order']['shipping_company']);
        $street1 = $dom->createElement('Street1', $data['order']['shipping_address']);
        $country = $dom->createElement('Country', $data['order']['shipping_country']);
        $zip = $dom->createElement('ZipCode', $data['order']['shipping_postcode']);
        $city = $dom->createElement('City', $data['order']['shipping_city']);


        $deliveryAddress->appendChild($title);
        $deliveryAddress->appendChild($firstName);
        $deliveryAddress->appendChild($lastName);
        $deliveryAddress->appendChild($company);
        $deliveryAddress->appendChild($street1);
        $deliveryAddress->appendChild($country);
        $deliveryAddress->appendChild($zip);
        $deliveryAddress->appendChild($city);

        $invoiceAddress = $dom->createElement('InvoiceAddress');

        $title = $dom->createElement('Title', $data['order']['prefix']);
        $firstName = $dom->createElement('FirstName', $data['order']['first_name']);
        $lastName = $dom->createElement('LastName', $data['order']['last_name']);
        $email = $dom->createElement('Email', $data['order']['email']);
        $company = $dom->createElement('Company', $data['order']['company']);
        $street1 = $dom->createElement('Street1', $data['order']['address']);
        $country = $dom->createElement('Country', $data['order']['country']);
        $zip = $dom->createElement('ZipCode', $data['order']['postcode']);
        $city = $dom->createElement('City', $data['order']['city']);

        $invoiceAddress->appendChild($title);
        $invoiceAddress->appendChild($firstName);
        $invoiceAddress->appendChild($lastName);
        $invoiceAddress->appendChild($email);
        $invoiceAddress->appendChild($company);
        $invoiceAddress->appendChild($street1);
        $invoiceAddress->appendChild($country);
        $invoiceAddress->appendChild($zip);
        $invoiceAddress->appendChild($city);

        $addressElement->appendChild($deliveryAddress);
        $addressElement->appendChild($invoiceAddress);

        $dom->appendChild($addressElement);

        $dom->formatOutput = true;

        return $dom;
    }

    public function getCartNode()
    {
        $data = $this->data;
        $dom = new \DOMDocument();

        $cartElement = $dom->createElement('Cart');

        foreach ($data['items'] as $item) {
            $itemNode = $dom->createElement('Item');

            $posNo = $dom->createElement('PosNo', 'title');
            $erpProductNo = $dom->createElement('ErpProductNo', $item['product_id']);
            $MagentoProductNo = $dom->createElement('MagentoProductNo', $item['product_id']);
            $MagentoProductSku = $dom->createElement('MagentoProductSku', $item['sku']);
            $productName = $dom->createElement('ProductName', $item['product_name']);
            $qty = $dom->createElement('Quantity', $item['quantity']);
            $pricePerItem = $dom->createElement('PricePerItem', $item['price']);

            $itemNode->appendChild($posNo);
            $itemNode->appendChild($erpProductNo);
            $itemNode->appendChild($MagentoProductNo);
            $itemNode->appendChild($MagentoProductSku);
            $itemNode->appendChild($productName);
            $itemNode->appendChild($qty);
            $itemNode->appendChild($pricePerItem);

            $cartElement->appendChild($itemNode);
            $dom->appendChild($cartElement);
        }


        $dom->formatOutput = true;

        return $dom;
    }
}