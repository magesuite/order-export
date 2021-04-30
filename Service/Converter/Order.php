<?php

namespace MageSuite\OrderExport\Service\Converter;

class Order implements \MageSuite\OrderExport\Service\Converter\OrderInterface
{
    /**
     * @var \Magento\Shipping\Model\Config\Source\Allmethods
     */
    protected $shippingMethods;

    /**
     * @var \MageSuite\OrderExport\Service\Converter\OrderItemInterface
     */
    protected $orderItemConverter;

    /**
     * @var \MageSuite\OrderExport\Service\Converter\OrderAdditionalFieldsInterface
     */
    protected $orderAdditionalFields;

    public function __construct(
        \Magento\Shipping\Model\Config\Source\Allmethods $shippingMethods,
        \MageSuite\OrderExport\Service\Converter\OrderItemInterface $orderItemConverter,
        \MageSuite\OrderExport\Service\Converter\OrderAdditionalFieldsInterface $orderAdditionalFields
    )
    {
        $this->shippingMethods = $shippingMethods;
        $this->orderItemConverter = $orderItemConverter;
        $this->orderAdditionalFields = $orderAdditionalFields;
    }

    public function convert(\Magento\Sales\Model\Order $order): array
    {
        $result = [
            'order' => $order->toArray(),
            'items' => [],
            'increment_id' => $order->getIncrementId()
        ];

        $billingAddress = $order->getBillingAddress();
        $result['order']['billing'] = $this->getAddressFields($billingAddress);

        $shippingAddress = $order->getShippingAddress();
        $result['order']['shipping'] = $this->getAddressFields($shippingAddress);

        $result['order']['payment_method'] = $order->getPayment()->getMethod();

        $this->addAdditionalFieldsToOrder($result['order'], $order);

        foreach ($order->getAllItems() as $item) {
            $convertedItem = $this->orderItemConverter->convert($item, $result['order']);
            if (empty($convertedItem)) {
                continue;
            }
            
            $result['items'][$item->getItemId()] = $convertedItem;

        }

        return $result;
    }

    public function addAdditionalFieldsToOrder(&$resultOrder, $order)
    {
        $resultOrder = array_merge($resultOrder, $this->orderAdditionalFields->getAdditionalFields($order));
    }

    public function getAddressFields($address)
    {
        if (empty($address)) {
            return [
                'prefix' => '',
                'name' => '',
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'company' => '',
                'street' => '',
                'city' => '',
                'postcode' => '',
                'region' => '',
                'country' => '',
                'telephone' => ''
            ];
        }

        return [
            'prefix' => $address->getPrefix(),
            'name' => $address->getFirstname() . ' ' . $address->getLastname(),
            'first_name' => $address->getFirstname(),
            'last_name' => $address->getLastname(),
            'email' => $address->getEmail(),
            'company' => $address->getCompany(),
            'street' => implode(' ', $address->getStreet()),
            'city' => $address->getCity(),
            'postcode' => $address->getPostcode(),
            'region' => $address->getRegion(),
            'country' => $address->getCountryId(),
            'telephone' => $address->getTelephone()
        ];
    }

    protected function getShipmentMethodTitle(\Magento\Sales\Model\Order $order)
    {
        $shippingMethod = $order->getShippingMethod();

        if (empty($shippingMethod)) {
            return '';
        }

        $shippingMethods = $this->shippingMethods->toOptionArray();
        list($carrierCode, $carrierMethod) = explode('_', $shippingMethod, 2);
        if (array_key_exists($carrierMethod, $shippingMethods)) {
            return $shippingMethods[$carrierMethod]['label'];
        }

        if (!array_key_exists($carrierCode, $shippingMethods)) {
            return '';
        }

        foreach ($shippingMethods[$carrierCode]['value'] as $method) {
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
