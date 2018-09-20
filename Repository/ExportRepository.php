<?php

namespace MageSuite\OrderExport\Repository;

class ExportRepository
{
    /**
     * @var \MageSuite\OrderExport\Model\ResourceModel\Export
     */
    private $exportResourceModel;

    /**
     * @var \MageSuite\OrderExport\Model\ExportFactory
     */
    private $exportFactory;

    /**
     * @var \MageSuite\OrderExport\Model\Collections\ExportFactory
     */
    private $exportCollectionFactory;


    public function __construct(
        \MageSuite\OrderExport\Model\ResourceModel\Export $exportResourceModel,
        \MageSuite\OrderExport\Model\ExportFactory $exportFactory,
        \MageSuite\OrderExport\Model\Collections\ExportFactory $exportCollectionFactory
    )
    {
        $this->exportResourceModel = $exportResourceModel;
        $this->exportFactory = $exportFactory;
        $this->exportCollectionFactory = $exportCollectionFactory;
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

        return $export->load($id);
    }

    public function getLastExport()
    {
        $collection = $this->exportCollectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addOrder('finished_at');

        return $collection->getFirstItem();
    }

    public function save(\MageSuite\OrderExport\Model\Export $export)
    {
        return $this->exportResourceModel->save($export);
    }

}