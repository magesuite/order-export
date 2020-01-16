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
    public function getById($id);

    /**
     * @param \MageSuite\OrderExport\Api\Data\ExportLogInterface $export
     * @return \MageSuite\OrderExport\Api\Data\ExportLogInterface
     */
    public function save(\MageSuite\OrderExport\Api\Data\ExportLogInterface $export);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Creativestyle\CustomizationKrueger\Model\SalespersonSearchResult
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null);
}
