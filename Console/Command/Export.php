<?php

namespace MageSuite\OrderExport\Console\Command;


class Export extends \Symfony\Component\Console\Command\Command
{

    /**
     * @var \MageSuite\OrderExport\Repository\OrderRepositoryFactory
     */
    private $orderRepository;

    /**
     * @var \MageSuite\OrderExport\Repository\ExportRepository
     */
    private $exportRepository;

    /**
     * @var \MageSuite\OrderExport\Services\Export\Converter\OrderCollection
     */
    private $orderCollectionConverter;

    /**
     * @var \MageSuite\OrderExport\Services\Export\Exporter\CSV
     */
    private $exporter;

    /**
     * @var \MageSuite\OrderExport\Services\FTP\UploaderFactory
     */
    private $uploader;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Framework\Event\Manager
     */
    private $eventManager;

    public function __construct(
        \MageSuite\OrderExport\Repository\OrderRepositoryFactory $orderRepository,
        \MageSuite\OrderExport\Repository\ExportRepository $exportRepository,
        \MageSuite\OrderExport\Services\Export\Converter\OrderCollectionFactory $orderCollectionConverter,
        \MageSuite\OrderExport\Services\Export\ExporterFactory $exporterFactory,
        \MageSuite\OrderExport\Services\FTP\UploaderFactory $uploader,
        \Magento\Framework\App\State $state,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Event\Manager $eventManager
    )
    {
        parent::__construct();

        $this->orderRepository = $orderRepository;
        $this->exportRepository = $exportRepository;
        $this->orderCollectionConverter = $orderCollectionConverter;
        $this->exporter = $exporterFactory;
        $this->uploader = $uploader;
        $this->state = $state;
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;
    }

    protected function configure()
    {
        $this->addOption(
            'date-from',
            '-f',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Type Date from which order will be exported (format Y-m-d), if empty orders from current date will be taken'
        );

        $this->addOption(
            'date-to',
            '-t',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Type Date to which order will be exported (format Y-m-d), if empty orders from current date will be taken'
        );

        $this->addArgument(
            'status',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Search Status'
        );

        $this->addArgument(
            'new-status',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'New status for exported orders'
        );

        $this
            ->setName('orderexport:export:manual')
            ->setDescription('Manual run order export');
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        $export = $this->exportRepository->create();

        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $status = $input->getArgument('status');
        if (empty($status)) {
            $status = 'all';
        }
        $newStatus = $input->getArgument('new-status');

        $dateFrom = $input->getOption('date-from');
        $dateTo = $input->getOption('date-to');

        if (!$dateFrom) {
            $dateFrom = null;
        }

        if (!$dateTo) {
            $dateTo = null;
        }

        $orderRepository = $this->orderRepository->create();
        $exportOrdersDaily = $this->scopeConfig->getValue('orderexport/automatic/export_orders_daily', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $exportOrdersStrategy = $this->scopeConfig->getValue('orderexport/automatic/export_strategy', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($exportOrdersDaily) {
            $orders = $orderRepository->getOrdersByDate($status, $dateFrom, $dateTo);
        } else {
            $orders = $orderRepository->getOrders($status);
        }
        $orderCollectionConverter = $this->orderCollectionConverter->create();
        $converted = $orderCollectionConverter->toArray($orders);

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

        $export->setResult('manual', $filename, $status, $result);
        $this->exportRepository->create()->save($export);


        if (!empty($newStatus)) {
            /** @var \Magento\Sales\Model\Order $order */
            foreach ($orders as $order) {
                $order->addStatusToHistory($newStatus, 'Order has been exported manually');
                $order->save();
            }
        }

        foreach ($result['ordersData'] as $order) {
            $uploaderFactory = $this->uploader->create();
            $uploaderFactory->upload($order['filepath']);
        }

        $this->eventManager->dispatch('cs_cron_orderexport_validate', ['result' => $result, 'export' => $export]);
    }

}


