<?php

namespace MageSuite\OrderExport\Model;

class ExportRepository implements \MageSuite\OrderExport\Api\ExportRepositoryInterface
{
    /**
     * @var \MageSuite\OrderExport\Model\ResourceModel\Export
     */
    protected $exportResource;

    /**
     * @var \MageSuite\OrderExport\Api\Data\ExportInterfaceFactory
     */
    protected $exportFactory;

    /**
     * @var \MageSuite\OrderExport\Model\ResourceModel\Export\CollectionFactory
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
     * @var \MageSuite\OrderExport\Api\Data\ExportSearchResultInterfaceFactory
     */
    protected $exportSearchResultFactory;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \MageSuite\OrderExport\Model\ResourceModel\Export $exportResource,
        \MageSuite\OrderExport\Api\Data\ExportInterfaceFactory $exportFactory,
        \MageSuite\OrderExport\Model\ResourceModel\Export\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor,
        \MageSuite\OrderExport\Api\Data\ExportSearchResultInterfaceFactory $exportSearchResultFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration
    ) {
        $this->exportResource = $exportResource;
        $this->exportFactory = $exportFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->exportSearchResultFactory = $exportSearchResultFactory;
        $this->configuration = $configuration;
    }

    public function create()
    {
        $export = $this->exportFactory->create();
        $export->setStartedAt(new \DateTime());

        return $export;
    }

    public function getById($id)
    {
        $export = $this->exportFactory->create();
        $export->load($id);

        if (!$export->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Export with id "%1" does not exist.', $id));
        }

        return $export;
    }

    public function save(\MageSuite\OrderExport\Api\Data\ExportInterface $export)
    {
        if (!$export->getSuccess() && !$this->configuration->logAllExports()) {
            return $export;
        }

        return $this->exportResource->save($export);
    }

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        $collection = $this->collectionFactory->create();

        if (empty($searchCriteria)) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $searchResult = $this->exportSearchResultFactory->create();
        $searchResult
            ->setItems($collection->getItems())
            ->setTotalCount($collection->getSize())
            ->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
