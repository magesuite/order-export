<?php

namespace MageSuite\OrderExport\Service;

class Exporter extends \Magento\Framework\DataObject
{
    /**
     * @var \MageSuite\OrderExport\Api\ExportLogRepositoryInterface
     */
    protected $exportLogRepository;

    /**
     * @var \MageSuite\OrderExport\Model\OrderFilterInterface
     */
    protected $orderFilter;

    /**
     * @var \MageSuite\OrderExport\Model\OrderRepositoryInterface
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
        \MageSuite\OrderExport\Api\ExportLogRepositoryInterface $exportLogRepository,
        \MageSuite\OrderExport\Model\OrderRepositoryInterface $orderRepository,
        \MageSuite\OrderExport\Model\OrderFilterInterface $orderFilter,
        \MageSuite\OrderExport\Service\Export\ExporterFactory $exporterFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        \Magento\Framework\Event\Manager $eventManager,
        array $data = []
    ) {
        parent::__construct($data);

        $this->exportLogRepository = $exportLogRepository;
        $this->orderRepository = $orderRepository;
        $this->orderFilter = $orderFilter;
        $this->exporterFactory = $exporterFactory;
        $this->configuration = $configuration;
        $this->eventManager = $eventManager;
    }

    public function execute()
    {
        $exportLog = $this->exportLogRepository->create();

        $filters = $this->orderFilter->getFilters($this->getData());
        $orders = $this->orderRepository->getOrdersList($filters);

        if ($orderCount = count($orders)) {

            /** @var \MageSuite\OrderExport\Service\Export\ExporterInterface $exporter */
            $exporter = $this->exporterFactory->create();
            $result = $exporter->export($orders);

            $exportFileName = $this->getExportFileName($result, $orderCount);
        } else {
            $exportFileName = '';
            $result = ['exportedCount' => 0, 'exportedIds' => [], 'generatedFiles' => []];
        }

        $usedOrderFilters = $this->orderFilter->getUsedOrderFilters($filters);

        $exportLog
            ->setType($this->getType())
            ->setExportedFilename($exportFileName)
            ->setUsedOrderFilters($usedOrderFilters)
            ->setExportedCount($result['exportedCount'])
            ->setExportedIds(implode(', ', $result['exportedIds']))
            ->setFinishedAt(new \DateTime());

        $this->exportLogRepository->save($exportLog);

        $this->eventManager->dispatch(
            'orderexport_export_after',
            [
                'orders' => $orders,
                'result' => $result,
                'type' => $this->getType(),
                'status_after_export' => $this->getStatusAfterExport()
            ]
        );

        $this->eventManager->dispatch(
            'orderexport_export_validate',
            [
                'result' => $result,
                'export_log' => $exportLog
            ]
        );

        return $result;
    }

    protected function getExportFileName($result, $orderCount)
    {
        if ($this->configuration->getExportStrategy() == \MageSuite\OrderExport\Model\Config\Source\Export\Strategy::EXPORT_STRATEGY_SEPARATED || $orderCount == 1) {
            $fileName = $result['generatedFiles'][0]['fileName'];
        } else {
            $lastOrder = end($result['generatedFiles']);
            $fileName = $result['generatedFiles'][0]['fileName'] . ' - ' . $lastOrder['fileName'];
        }

        return $fileName;
    }
}
