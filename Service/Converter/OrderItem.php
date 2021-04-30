<?php

namespace MageSuite\OrderExport\Service\Converter;

class OrderItem implements \MageSuite\OrderExport\Service\Converter\OrderItemInterface
{
    /**
     * @var \MageSuite\OrderExport\Service\Converter\OrderItemAdditionalFieldsInterface
     */
    protected $orderItemAdditionalFields;

    public function __construct(\MageSuite\OrderExport\Service\Converter\OrderItemAdditionalFieldsInterface $orderItemAdditionalFields)
    {
        $this->orderItemAdditionalFields = $orderItemAdditionalFields;
    }

    public function convert(\Magento\Sales\Api\Data\OrderItemInterface $orderItem, $order): array
    {
        $parentItem = $orderItem->getParentItem();
        if ($parentItem && $parentItem->getProductType() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
            return [];
        }

        $result = $orderItem->toArray();

        $result['erp_product_id'] = $orderItem->getProductId();
        $result['product_name'] = $orderItem->getName();
        $result['quantity'] = (float)$orderItem->getQtyOrdered();

        $this->addAdditionalFieldsToOrderItem($result, $orderItem, $order);

        return $result;
    }

    public function addAdditionalFieldsToOrderItem(&$result, $orderItem, $order)
    {
        $result = array_merge($result, $this->orderItemAdditionalFields->getAdditionalFields($orderItem, $order));
    }
}
