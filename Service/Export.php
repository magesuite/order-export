<?php

namespace MageSuite\OrderExport\Service;

class Export extends \Magento\Framework\DataObject
{
    /**
     * @var \MageSuite\OrderExport\Api\ExportRepositoryInterface
     */
    protected $exportRepository;

    /**
     * @var \MageSuite\OrderExport\Model\OrderFilterInterface
     */
    protected $orderFilter;

    /**
     * @var \MageSuite\OrderExport\Model\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \MageSuite\OrderExport\Service\Export\ExporterFactory
     */
    protected $exporterFactory;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    protected $eventManager;

    public function __construct(
        \MageSuite\OrderExport\Api\ExportRepositoryInterface $exportRepository,
        \MageSuite\OrderExport\Model\OrderRepository $orderRepository,
        \MageSuite\OrderExport\Model\OrderFilterInterface $orderFilter,
        \MageSuite\OrderExport\Service\Export\ExporterFactory $exporterFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        \Magento\Framework\Event\Manager $eventManager,
        array $data = []
    ) {
        parent::__construct($data);

        $this->exportRepository = $exportRepository;
        $this->orderRepository = $orderRepository;
        $this->orderFilter = $orderFilter;
        $this->exporterFactory = $exporterFactory;
        $this->configuration = $configuration;
        $this->eventManager = $eventManager;
    }

    public function execute()
    {
        $exportModel = $this->exportRepository->create();

        $filters = $this->orderFilter->getFilters($this->getData());
        $orders = $this->orderRepository->getOrdersList($filters);

        if ($orderCount = count($orders)) {

            /** @var \MageSuite\OrderExport\Service\Export\ExporterInterface $exporter */
            $exporter = $this->exporterFactory->create();
            $result = $exporter->export($orders);

            if ($this->configuration->getExportStrategy() == 'grouped' || $orderCount == 1) {
                $filename = $result['ordersData'][0]['filename'];
            } else {
                $lastOrder = (int)$orderCount - 1;
                $filename = $result['ordersData'][0]['filename'] . " - " . $result['ordersData'][$lastOrder]['filename'];
            }
        } else {
            $filename = '';
            $result = ['success' => 0, 'successIds' => [], 'ordersData' => []];
        }

        $exportModel
            ->setType($this->getType())
            ->setExportedFilename($filename)
            ->setSearchOrderStatus($this->getStatus())
            ->setSuccess($result['success'])
            ->setSuccessIds(implode(', ', $result['successIds']))
            ->setFinishedAt(new \DateTime());

        $this->exportRepository->save($exportModel);

        foreach ($result['ordersData'] as $order) {
            $this->eventManager->dispatch('orderexport_export_after', ['orders' => $orders, 'filePath' => $order['filepath'], 'type' => $this->getType(), 'new_status' => $this->getNewStatus()]);
        }

        $this->eventManager->dispatch('orderexport_export_validate', ['result' => $result, 'export' => $exportModel]);

        return $result;
    }
}
