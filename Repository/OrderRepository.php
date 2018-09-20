<?php

namespace MageSuite\OrderExport\Repository;

class OrderRepository
{

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
    )
    {
        $this->orderCollectionFactory = $collectionFactory;
    }

    /**
     * @param string $status
     * @return \Magento\Framework\DataObject[]
     */
    public function getOrders($status = 'all')
    {
        $collection = $this->orderCollectionFactory->create()->addFieldToSelect('*');
        if ($status != 'all') {
            $collection->addFieldToFilter('status', $status);
        }

        return $collection->getItems();
    }

    public function getOrdersByDate($status = 'all', $dateFrom = null, $dateTo = null)
    {
        $collection = $this->orderCollectionFactory->create()->addFieldToSelect('*');
        if ($status != 'all') {
            $collection->addFieldToFilter('status', $status);
        }
        $now = new \DateTime();

        if($dateFrom) {
            $from = new \DateTime($dateFrom);
            $collection
                ->addFieldToFilter('created_at', ['gteq' => $from->format('Y-m-d 00:00:00')]);

        } else {
            $collection
                ->addFieldToFilter('created_at', ['gteq' => $now->format('Y-m-d 00:00:00')]);
        }

        if($dateTo) {
            $to = new \DateTime($dateTo);
            $collection
                ->addFieldToFilter('created_at', ['lteq' => $to->format('Y-m-d 23:59:59')]);
        } else {
            $collection
                ->addFieldToFilter('created_at', ['lteq' => $now->format('Y-m-d 23:59:59')]);
        }

        return $collection->getItems();
    }
}