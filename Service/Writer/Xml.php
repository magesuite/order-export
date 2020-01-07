<?php

namespace MageSuite\OrderExport\Service\Writer;

class Xml implements \MageSuite\OrderExport\Service\Writer\WriterInterface
{
    protected $data;

    protected $fileHandler;

    public function openFile($filePath)
    {
        $this->checkPath($filePath);
        $this->fileHandler = fopen($filePath, 'w');
    }

    public function closeFile()
    {
        fclose($this->fileHandler);
    }

    public function write($data)
    {
        $strategy = $data['strategy'];

        unset($data['strategy']);

        if ($strategy == 'grouped') {
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
        fputs($this->fileHandler, $xml->saveXML());
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
        fputs($this->fileHandler, $xml->saveXML());
    }

    protected function checkPath($filePath)
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

        $orderDate = $dom->createElement('OrderDate', $data['order']['created_at']);

        $magentoOrderNo = $dom->createElement('MagentoOrderNo', $data['order']['increment_id']);

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
        $deliveryAddress->appendChild($title);

        $firstName = $dom->createElement('FirstName');
        $firstName->appendChild($dom->createTextNode($data['order']['shipping_first_name']));
        $deliveryAddress->appendChild($firstName);

        $lastName = $dom->createElement('LastName');
        $lastName->appendChild($dom->createTextNode($data['order']['shipping_last_name']));
        $deliveryAddress->appendChild($lastName);

        $company = $dom->createElement('Company');
        $company->appendChild($dom->createTextNode($data['order']['shipping_company']));
        $deliveryAddress->appendChild($company);

        $street1 = $dom->createElement('Street1');
        $street1->appendChild($dom->createTextNode($data['order']['shipping_address']));
        $deliveryAddress->appendChild($street1);

        $country = $dom->createElement('Country', $data['order']['shipping_country']);
        $deliveryAddress->appendChild($country);

        $zip = $dom->createElement('ZipCode', $data['order']['shipping_postcode']);
        $deliveryAddress->appendChild($zip);

        $city = $dom->createElement('City', $data['order']['shipping_city']);
        $deliveryAddress->appendChild($city);

        $addressElement->appendChild($deliveryAddress);

        $invoiceAddress = $dom->createElement('InvoiceAddress');

        $title = $dom->createElement('Title', $data['order']['prefix']);
        $invoiceAddress->appendChild($title);

        $firstName = $dom->createElement('FirstName');
        $firstName->appendChild($dom->createTextNode($data['order']['first_name']));
        $invoiceAddress->appendChild($firstName);

        $lastName = $dom->createElement('LastName');
        $lastName->appendChild($dom->createTextNode($data['order']['last_name']));
        $invoiceAddress->appendChild($lastName);

        $email = $dom->createElement('Email', $data['order']['email']);
        $invoiceAddress->appendChild($email);

        $company = $dom->createElement('Company');
        $company->appendChild($dom->createTextNode($data['order']['company']));
        $invoiceAddress->appendChild($company);

        $street1 = $dom->createElement('Street1');
        $street1->appendChild($dom->createTextNode($data['order']['address']));
        $invoiceAddress->appendChild($street1);

        $country = $dom->createElement('Country', $data['order']['country']);
        $invoiceAddress->appendChild($country);

        $zip = $dom->createElement('ZipCode', $data['order']['postcode']);
        $invoiceAddress->appendChild($zip);

        $city = $dom->createElement('City', $data['order']['city']);
        $invoiceAddress->appendChild($city);

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

        $i = 1;

        foreach ($data['items'] as $item) {
            $itemNode = $dom->createElement('Item');

            $posNo = $dom->createElement('PosNo', $i);
            $itemNode->appendChild($posNo);

            $erpProductNo = $dom->createElement('ErpProductNo', $item['product_id']);
            $itemNode->appendChild($erpProductNo);

            $MagentoProductNo = $dom->createElement('MagentoProductNo', $item['product_id']);
            $itemNode->appendChild($MagentoProductNo);

            $MagentoProductSku = $dom->createElement('MagentoProductSku', $item['sku']);
            $itemNode->appendChild($MagentoProductSku);

            $productName = $dom->createElement('ProductName');
            $productName->appendChild($dom->createTextNode($item['product_name']));
            $itemNode->appendChild($productName);

            $qty = $dom->createElement('Quantity', $item['quantity']);
            $itemNode->appendChild($qty);

            $pricePerItem = $dom->createElement('PricePerItem', $item['price']);
            $itemNode->appendChild($pricePerItem);

            $cartElement->appendChild($itemNode);
            $dom->appendChild($cartElement);

            $i++;
        }

        $dom->formatOutput = true;

        return $dom;
    }
}
