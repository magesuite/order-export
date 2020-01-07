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

    public function __construct(
        \Magento\Shipping\Model\Config\Source\Allmethods $shippingMethods,
        \MageSuite\OrderExport\Service\Converter\OrderItemInterface $orderItemConverter
    ) {
        $this->shippingMethods = $shippingMethods;
        $this->orderItemConverter = $orderItemConverter;
    }

    public function convert(\Magento\Sales\Model\Order $order): array
    {
        $result = [
            'order' => $order->toArray(),
            'items' => []
        ];

        foreach ($order->getAllItems() as $item) {
            $result['items'][] = $this->orderItemConverter->convert($item);
        }

        $this->addAdditionalFieldsToOrder($result['order'], $order);

        return $result;
    }

    public function addAdditionalFieldsToOrder(&$resultOrder, $order)
    {
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();

        $additionalFields = [
            'prefix' => $billingAddress->getPrefix(),
            'name' => $billingAddress->getFirstname() . ' ' . $billingAddress->getLastname(),
            'first_name' => $billingAddress->getFirstname(),
            'last_name' => $billingAddress->getLastname(),
            'telephone' => $billingAddress->getTelephone(),
            'email' => $billingAddress->getEmail(),
            'company' => $billingAddress->getCompany(),
            'address' => implode(' ', $billingAddress->getStreet()),
            'city' => $billingAddress->getCity(),
            'postcode' => $billingAddress->getPostcode(),
            'region' => $billingAddress->getRegion(),
            'country' => $billingAddress->getCountryId(),
            'shipping_prefix' => $shippingAddress->getPrefix(),
            'shipping_name' => $shippingAddress->getFirstname() . ' ' . $shippingAddress->getLastname(),
            'shipping_first_name' => $shippingAddress->getFirstname(),
            'shipping_last_name' => $shippingAddress->getLastname(),
            'shipping_company' => $shippingAddress->getCompany(),
            'shipping_address' => implode(' ', $shippingAddress->getStreet()),
            'shipping_city' => $shippingAddress->getCity(),
            'shipping_postcode' => $shippingAddress->getPostcode(),
            'shipping_region' => $shippingAddress->getRegion(),
            'shipping_country' => $shippingAddress->getCountryId(),
            'shipping_amount' => number_format($order->getBaseShippingInclTax(), 2),
            'shipping_method' => $this->getShipmentMethodTitle($order),
            'payment_method' => $order->getPayment()->getMethod()
        ];

        $resultOrder = array_merge($resultOrder, $additionalFields);
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
