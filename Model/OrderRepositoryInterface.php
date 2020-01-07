<?php

namespace MageSuite\OrderExport\Model;

interface OrderRepositoryInterface
{
    /**
     * @param array $filtersData
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getOrdersList($filtersData);

    /**
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($orderId);
}
