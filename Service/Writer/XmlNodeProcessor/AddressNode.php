<?php

namespace MageSuite\OrderExport\Service\Writer\XmlNodeProcessor;

class AddressNode implements \MageSuite\OrderExport\Service\Writer\XmlNodeProcessorInterface
{
    protected $addressGroups = [
        ['nodeName' => 'DeliveryAddress', 'type' => 'shipping'],
        ['nodeName' => 'InvoiceAddress', 'type' => 'billing']
    ];

    public function execute($xml, $order)
    {
        $addressElement = $xml->createElement('Address');

        foreach ($this->getAddressGroup() as $addressGroup) {
            $node = $xml->createElement($addressGroup['nodeName']);

            foreach ($this->getAddressFieldsMap($order, $addressGroup['type']) as $name => $value) {
                $element = $xml->createElement($name);
                $element->appendChild($xml->createTextNode($value ?: ''));
                $node->appendChild($element);
            }

            $addressElement->appendChild($node);
        }

        return $addressElement;
    }

    public function getAddressGroup()
    {
        return $this->addressGroups;
    }

    public function getAddressFieldsMap($order, $type)
    {
        $address = $order['order'][$type];

        return [
            'Title' => $address['prefix'],
            'FirstName' => $address['first_name'],
            'LastName' => $address['last_name'],
            'Email' => $address['email'],
            'Company' => $address['company'],
            'Street1' => $address['street'],
            'ZipCode' => $address['postcode'],
            'City' => $address['city'],
            'Country' => $address['country']
        ];
    }
}
