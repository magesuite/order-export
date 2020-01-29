<?php
namespace MageSuite\OrderExport\Api;

interface ExportStatusRepositoryInterface
{

    /**
     * @param $order
     * @param $stepData
     * @return \MageSuite\OrderExport\Api\Data\ExportStatusInterface
     */
    public function addStatus($order, $stepData);

    /**
     * @param $orderId
     * @return \Magento\Framework\DataObject|null
     */
    public function getById($orderId);

    /**
     * @param string $incrementId
     * @param string $status
     * @return \MageSuite\OrderExport\Api\Data\ExportStatusInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function addStatusByApi($incrementId, $status);
}
