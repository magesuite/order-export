<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\ExportLog;

class GeneratePost extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    const EXPORT_FILENAME_FORMAT = 'order_%s_%s.%s';

    /**
     * @var \MageSuite\OrderExport\Model\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \MageSuite\OrderExport\Service\Export\ExporterFactory
     */
    protected $exporterFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MageSuite\OrderExport\Model\OrderRepositoryInterface $orderRepository,
        \MageSuite\OrderExport\Service\Export\ExporterFactory $exporterFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \MageSuite\OrderExport\Helper\Configuration $configuration
    ) {
        parent::__construct($context);
        $this->orderRepository = $orderRepository;
        $this->exporterFactory = $exporterFactory;
        $this->directoryList = $directoryList;
        $this->fileFactory = $fileFactory;
        $this->configuration = $configuration;
    }

    public function execute()
    {
        $status = $this->getRequest()->getParam('order_status');

        $today = (new \DateTime())->format('Y-m-d');
        $fileName = sprintf(self::EXPORT_FILENAME_FORMAT, $status, $today, $this->configuration->getExportFileType());
        $filePath = sprintf('%s/manual/%s', $this->configuration->getUploadPath(), $fileName);

        $filters = [
            ['field' => 'status', 'value' => $status, 'condition' => 'eq']
        ];

        $orders = $this->orderRepository->getOrdersList($filters);

        $exporter = $this->exporterFactory->create();
        $exporter->export($orders);

        $this->fileFactory->create(
            $fileName,
            file_get_contents($filePath),
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
        );
    }
}
