<?php

namespace MageSuite\OrderExport\Services\Export\Converter;

use MageSuite\OrderExport\Services\Export\Converter\OrderItem as OrderItemConverter;

class Order extends \MageSuite\OrderExport\Services\Export\Converter\AbstractOrder
{

    /**
     * @var OrderItemConverter
     */
    protected $orderItemConverter;

    public function __construct(
        \MageSuite\OrderExport\Services\Export\Converter\OrderItem $orderItemConverter,
        \Magento\Shipping\Model\Config\Source\Allmethods $allmethods
    )
    {
        parent::__construct($allmethods);
        $this->orderItemConverter = $orderItemConverter;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    public function toArray(\Magento\Sales\Model\Order $order)
    {
        $incrementId = $order->getIncrementId();
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $orderData = [
            'increment_id' => $incrementId,
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
            'payment_method' => $order->getPayment()->getMethod(),
            'creation_date' => $order->getCreatedAt(),
            'store_id' => $order->getStore()->getId(),
            'customer_id' => ($order->getCustomerId()) ? $order->getCustomerId() : ''
        ];
        $itemsData = [];
        foreach ($order->getAllVisibleItems() as $item) {
            $itemData = $this->orderItemConverter->toArray($item);
            $itemData['increment_id'] = $incrementId;
            $itemsData[] = $itemData;
        }

        $result = ['increment_id' => $incrementId, 'order' => $orderData, 'items' => $itemsData];

        return $result;
    }
}