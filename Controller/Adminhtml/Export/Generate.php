<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\Export;

use MageSuite\OrderExport\Services\Export\Converter\OrderCollection as OrderCollectionConverter;

class Generate extends \Magento\Backend\App\Action
{

    /**
     * @var \MageSuite\OrderExport\Repository\OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderCollectionConverter
     */
    private $orderCollectionConverter;

    /**
     * @var \MageSuite\OrderExport\Services\Export\ExporterFactory
     */
    private $exporterFactory;

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

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\OrderExport\Repository\OrderRepository $orderRepository,
        \MageSuite\OrderExport\Services\Export\Converter\OrderCollection $orderCollectionConverter,
        \MageSuite\OrderExport\Services\Export\ExporterFactory $exporterFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context);
        $this->orderRepository = $orderRepository;
        $this->orderCollectionConverter = $orderCollectionConverter;
        $this->exporterFactory = $exporterFactory;
        $this->directoryList = $directoryList;
        $this->fileFactory = $fileFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $status = $this->getRequest()->getParam('order_status');
        $filename = 'order_' . $status . '_' . (new \DateTime())->format('Y-m-d') . '.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . '/orderexport/manual/' . $filename;
        $orders = $this->orderRepository->getOrders($status);
        $converted = $this->orderCollectionConverter->toArray($orders);
        $exportType = $this->scopeConfig->getValue('orderexport/automatic/export_file_type', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $exporter = $this->exporterFactory->create($exportType);
        $exporter->export($converted, $filePath);
        $this->fileFactory->create(
            'orders_export.csv',
            file_get_contents($filePath),
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/csv'
        );

    }

}