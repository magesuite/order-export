<?php
namespace MageSuite\OrderExport\Api;

interface ExportRepositoryInterface
{
    /**
     * @return \MageSuite\OrderExport\Api\Data\ExportInterface
     */
    public function create();

    /**
     * @param int $id
     * @return \MageSuite\OrderExport\Api\Data\ExportInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \MageSuite\OrderExport\Api\Data\ExportInterface $export
     * @return \MageSuite\OrderExport\Api\Data\ExportInterface
     */
    public function save(\MageSuite\OrderExport\Api\Data\ExportInterface $export);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Creativestyle\CustomizationKrueger\Model\SalespersonSearchResult
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null);
}
