<?php

namespace MageSuite\OrderExport\Model\Config\Source\Order;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
     */
    private $statusCollectionFactory;

    public function __construct(\Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory)
    {
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * Get status options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->statusCollectionFactory->create()->toOptionArray();
    }
}