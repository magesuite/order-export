<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Service;

class Exporter extends \Magento\Framework\DataObject
{
    public function __construct(
        protected \MageSuite\OrderExport\Api\ExportLogRepositoryInterface $exportLogRepository,
        protected \MageSuite\OrderExport\Model\OrderRepositoryInterface $orderRepository,
        protected \MageSuite\OrderExport\Model\OrderFilterInterface $orderFilter,
        protected \MageSuite\OrderExport\Service\Export\ExporterFactory $exporterFactory,
        protected \MageSuite\OrderExport\Helper\Configuration $configuration,
        protected \Magento\Framework\Event\Manager $eventManager,
        protected \MageSuite\OrderExport\Service\Notifier $notifier,
        array $data = []
    ) {
        parent::__construct($data);
    }

    public function execute()
    {
        $exportLog = $this->exportLogRepository->create();

        $filters = $this->orderFilter->getFilters($this->getData());
        $orders = $this->orderRepository->getOrdersList($filters);

        if ($orderCount = count($orders)) {
            /** @var \MageSuite\OrderExport\Service\Export\ExporterInterface $exporter */
            $exporter = $this->exporterFactory->create();
            try {
                $result = $exporter->export($orders);
            } catch (\Exception $e) {
                $result = ['exportedCount' => 0, 'exportedIds' => [], 'generatedFiles' => [], 'errors' => [$e->getMessage()]];
            }

            $exportFileName = $this->getExportFileName($result, $orderCount);
        } else {
            $exportFileName = '';
            $result = ['exportedCount' => 0, 'exportedIds' => [], 'generatedFiles' => []];
        }

        $usedOrderFilters = $this->orderFilter->getUsedOrderFilters($filters);
        $resultType = match (true) {
            $result['exportedCount'] == $orderCount => \MageSuite\OrderExport\Enum\ResultType::SUCCESS->value,
            $result['exportedCount'] > 0 => \MageSuite\OrderExport\Enum\ResultType::PARTIAL_SUCCESS->value,
            default => \MageSuite\OrderExport\Enum\ResultType::FAILURE->value,
        };

        if ($resultType !== \MageSuite\OrderExport\Enum\ResultType::SUCCESS->value) {
            $exportLog->setErrors(implode("\n", $result['errors']) ?? 'Missing error logs.');
            $this->notifier->notify(
                'Order export ' . $resultType,
                sprintf("Order export %s.\nExported %d out of %d orders.\nCheck logs for details.", $resultType, $result['exportedCount'], $orderCount)
            );
        }

        $exportLog
            ->setType($this->getType())
            ->setExportedFilename($exportFileName)
            ->setUsedOrderFilters($usedOrderFilters)
            ->setExportedCount($result['exportedCount'])
            ->setExportedIds(implode(', ', $result['exportedIds']))
            ->setResultType($resultType)
            ->setFinishedAt(new \DateTime());

        $this->exportLogRepository->save($exportLog);

        $this->eventManager->dispatch(
            'orderexport_export_after',
            [
                'orders' => $orders,
                'result' => $result,
                'type' => $this->getType(),
                'status_after_export' => $this->getStatusAfterExport(),
            ]
        );

        $this->eventManager->dispatch(
            'orderexport_export_validate',
            [
                'result' => $result,
                'export_log' => $exportLog,
            ]
        );

        return $result;
    }

    protected function getExportFileName($result, $orderCount)
    {
        if (empty($result['generatedFiles'])) {
            return null;
        }

        if ($this->configuration->getExportStrategy() == \MageSuite\OrderExport\Model\Config\Source\Export\Strategy::EXPORT_STRATEGY_GROUPED || $orderCount == 1) {
            $fileName = $result['generatedFiles'][0]['fileName'];
        } else {
            $lastOrder = end($result['generatedFiles']);
            $fileName = $result['generatedFiles'][0]['fileName'] . ' - ' . $lastOrder['fileName'];
        }

        return $fileName;
    }
}
