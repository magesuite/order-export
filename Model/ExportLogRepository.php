<?php

namespace MageSuite\OrderExport\Model;

class ExportLogRepository implements \MageSuite\OrderExport\Api\ExportLogRepositoryInterface
{
    /**
     * @var \MageSuite\OrderExport\Model\ResourceModel\ExportLog
     */
    protected $exportLogResource;

    /**
     * @var \MageSuite\OrderExport\Api\Data\ExportLogInterfaceFactory
     */
    protected $exportLogFactory;

    /**
     * @var \MageSuite\OrderExport\Model\ResourceModel\ExportLog\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var \MageSuite\OrderExport\Api\Data\ExportLogSearchResultInterfaceFactory
     */
    protected $exportLogSearchResultFactory;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \MageSuite\OrderExport\Model\ResourceModel\ExportLog $exportLogResource,
        \MageSuite\OrderExport\Api\Data\ExportLogInterfaceFactory $exportLogFactory,
        \MageSuite\OrderExport\Model\ResourceModel\ExportLog\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \MageSuite\OrderExport\Api\Data\ExportLogSearchResultInterfaceFactory $exportLogSearchResultFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration
    ) {
        $this->exportLogResource = $exportLogResource;
        $this->exportLogFactory = $exportLogFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->exportLogSearchResultFactory = $exportLogSearchResultFactory;
        $this->configuration = $configuration;
    }

    public function create()
    {
        $export = $this->exportLogFactory->create();
        $export->setStartedAt(new \DateTime());

        return $export;
    }

    public function getById($id)
    {
        $export = $this->exportLogFactory->create();
        $export->load($id);

        if (!$export->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Export with id "%1" does not exist.', $id));
        }

        return $export;
    }

    public function save(\MageSuite\OrderExport\Api\Data\ExportLogInterface $export)
    {
        if (!$export->getExportedCount() && !$this->configuration->logAllExports()) {
            return $export;
        }

        return $this->exportLogResource->save($export);
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        $collection = $this->collectionFactory->create();

        if (empty($searchCriteria)) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $searchResult = $this->exportLogSearchResultFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult
            ->setTotalCount($collection->getSize())
            ->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
