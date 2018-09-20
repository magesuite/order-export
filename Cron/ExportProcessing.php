<?php

namespace MageSuite\OrderExport\Cron;

use MageSuite\OrderExport\Services\Export\Converter\OrderCollection as OrderCollectionConverter;
use Magento\Sales\Model\Order;

class ExportProcessing
{
    /**
     * @var \MageSuite\OrderExport\Repository\OrderRepository
     */
    private $orderRepository;

    /**
     * @var \MageSuite\OrderExport\Repository\ExportRepository
     */
    private $exportRepository;

    /**
     * @var OrderCollectionConverter
     */
    private $orderCollectionConverter;

    /**
     * @var \MageSuite\OrderExport\Services\Export\Exporter\CSV
     */
    private $exporter;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    private $eventManager;

    public function __construct(
        \MageSuite\OrderExport\Repository\OrderRepository $orderRepository,
        \MageSuite\OrderExport\Repository\ExportRepository $exportRepository,
        \MageSuite\OrderExport\Services\Export\Converter\OrderCollection $orderCollectionConverter,
        \MageSuite\OrderExport\Services\Export\ExporterFactory $exporterFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Event\Manager $eventManager
    )
    {
        $this->orderRepository = $orderRepository;
        $this->exportRepository = $exportRepository;
        $this->orderCollectionConverter = $orderCollectionConverter;
        $this->exporter = $exporterFactory;
        $this->directoryList = $directoryList;
        $this->fileFactory = $fileFactory;
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;

    }

    public function execute()
    {
        $active = $this->scopeConfig->getValue('orderexport/automatic/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$active) {
            return;
        }

        $export = $this->exportRepository->create();

        $exportOrdersDaily = $this->scopeConfig->getValue('orderexport/automatic/export_orders_daily', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $exportOrdersStrategy = $this->scopeConfig->getValue('orderexport/automatic/export_strategy', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if ($exportOrdersDaily) {
            $orders = $this->orderRepository->getOrdersByDate(Order::STATE_PROCESSING);
        } else {
            $orders = $this->orderRepository->getOrders(Order::STATE_PROCESSING);
        }

        $converted = $this->orderCollectionConverter->toArray($orders);
        if ($convertedCount = count($converted)) {
            $exportType = $this->scopeConfig->getValue('orderexport/automatic/export_file_type', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $exporter = $this->exporter->create($exportType);
            $result = $exporter->export($converted);
            if ($exportOrdersStrategy == 'grouped' OR $convertedCount == 1) {
                $filename = $result['ordersData'][0]['filename'];
            } else {
                $lastOrder = (int)$convertedCount - 1;
                $filename = $result['ordersData'][0]['filename'] . " - " . $result['ordersData'][$lastOrder]['filename'];
            }
        } else {
            $filename = '';
            $result = ['success' => 0, 'successIds' => [], 'ordersData' => []];
        }
        $export->setResult('cron', $filename, Order::STATE_PROCESSING, $result);
        $this->exportRepository->save($export);

        foreach ($result['ordersData'] as $order) {
            $this->eventManager->dispatch('cs_cron_orderexport_after', ['orders' => $orders, 'filePath' => $order['filepath']]);
        }

        $this->eventManager->dispatch('cs_cron_orderexport_validate', ['result' => $result, 'export' => $export]);
    }
}