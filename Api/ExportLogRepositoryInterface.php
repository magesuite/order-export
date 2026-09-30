<?php
namespace MageSuite\OrderExport\Api;

interface ExportLogRepositoryInterface
{
    /**
     * @return \MageSuite\OrderExport\Api\Data\ExportLogInterface
     */
    public function create();

    /**
     * @param int $id
     * @return \MageSuite\OrderExport\Api\Data\ExportLogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): \MageSuite\OrderExport\Api\Data\ExportLogInterface;

    /**
     * @param \MageSuite\OrderExport\Api\Data\ExportLogInterface $export
     * @return \MageSuite\OrderExport\Api\Data\ExportLogInterface
     */
    public function save(\MageSuite\OrderExport\Api\Data\ExportLogInterface $export): \MageSuite\OrderExport\Api\Data\ExportLogInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \MageSuite\OrderExport\Api\Data\ExportLogSearchResultInterface
     */
    public function getList(?\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null): \MageSuite\OrderExport\Api\Data\ExportLogSearchResultInterface;
}
