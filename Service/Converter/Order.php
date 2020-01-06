<?php

namespace MageSuite\OrderExport\Service\Converter;

class Order implements \MageSuite\OrderExport\Service\Converter\OrderInterface
{



    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var \Creativestyle\CustomizationKruegerOrderExport\Service\Export\Converter\OrderItem
     */
    protected $orderItemConverter;

    protected $erpMerchantIdMap = [
        'default' => self::B2C_MERCHANT_ID,
        self::B2B_WEBSITE_CODE => self::B2B_MERCHANT_ID
    ];

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resource,
        \Creativestyle\CustomizationKruegerOrderExport\Service\Export\Converter\OrderItem $orderItemConverter,
        \Magento\Shipping\Model\Config\Source\Allmethods $allmethods
    ) {
        parent::__construct($allmethods);

        $this->storeManager = $storeManager;
        $this->resource = $resource;
        $this->orderItemConverter = $orderItemConverter;
    }

    protected function getShipmentMethodTitle(\Magento\Sales\Model\Order $order)
    {
        $shippingMethod = $order->getShippingMethod();
        if (empty($shippingMethod)) {
            return '';
        }

        $allMethods = $this->allmethods->toOptionArray(true);
        list($carrierCode, $carrierMethod) = explode('_', $shippingMethod, 2);
        if (array_key_exists($carrierMethod, $allMethods)) {
            return $allMethods[$carrierMethod]['label'];
        }

        if (!array_key_exists($carrierCode, $allMethods)) {
            return '';
        }
        foreach ($allMethods[$carrierCode]['value'] as $method) {
            if ($method['value'] != $shippingMethod) {
                continue;
            }

            return $this->removeCarrierCodeFromShippingTitle($method['label']);
        }

        return '';
    }


    public function convert(\Magento\Sales\Model\Order $order): array
    {
        $orderData = $order->toArray();

        return $orderData;
        /*

        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                continue;
            }

            $itemData = $this->orderItemConverter->toArray($item, $erpMerchantId);
            $itemData['increment_id'] = $incrementId;
            $itemsData[] = $itemData;
        }

        $incrementId = $order->getIncrementId();
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();

        $storeId = $order->getStore()->getId();

        $storeCode = $this->getStoreCode($storeId);
        $erpMerchantId = $this->getErpMerchantId($storeId);
        $txId = $this->getTxId($order);

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
            'store_id' => $storeId,
            'customer_id' => ($order->getCustomerId()) ? $order->getCustomerId() : 0,
            'txid' => $txId,
            'discount_amount' => $order->getDiscountAmount(),
            'erp_merchant_id' => $erpMerchantId,
            'store_code' => $storeCode
        ];

        $itemsData = [];

        foreach ($order->getAllItems() as $item) {
            if ($item->getProductType() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                continue;
            }

            $itemData = $this->orderItemConverter->toArray($item, $erpMerchantId);
            $itemData['increment_id'] = $incrementId;
            $itemsData[] = $itemData;
        }

        $orderData['tax_per_cart'] = $this->getTaxPerCart($itemsData);

        $result = ['increment_id' => $incrementId, 'order' => $orderData, 'items' => $itemsData];

        return $result;

        */
    }
}
