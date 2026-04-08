<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Controller\Adminhtml\ExportLog;

class GeneratePost extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const EXPORT_FILENAME_FORMAT = 'order_%s_%s.%s';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \MageSuite\OrderExport\Model\OrderRepositoryInterface $orderRepository,
        protected \MageSuite\OrderExport\Service\Export\ExporterFactory $exporterFactory,
        protected \MageSuite\OrderExport\Helper\Configuration $configuration,
        protected \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        protected \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        protected \Magento\Framework\Filesystem\Driver\File $driver
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\App\ResponseInterface
    {
        $status = $this->getRequest()->getParam('order_status');
        $today = (new \DateTime())->format('Y-m-d');
        $fileName = sprintf(self::EXPORT_FILENAME_FORMAT, $status, $today, $this->configuration->getExportFileType());
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        $filePath = sprintf('%s/manual/%s', $this->configuration->getUploadPath(), basename($fileName));
        $varDirectory = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $realVarDirectory = $this->driver->getRealPath($varDirectory);
        $realFilePath = $this->driver->getRealPath($this->driver->getParentDirectory($filePath));

        if ($realVarDirectory === false || $realFilePath === false || strpos($realFilePath, $realVarDirectory . DIRECTORY_SEPARATOR) !== 0) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('The export file path is not within the allowed directory.')
            );
        }

        $filters = [
            [
                'field' => 'status',
                'value' => $status,
                'condition' => 'eq'
            ]
        ];
        $orders = $this->orderRepository->getOrdersList($filters);
        $exporter = $this->exporterFactory->create();
        $exporter->export($orders);

        return $this->fileFactory->create(
            $fileName,
            $this->driver->fileGetContents($filePath),
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR
        );
    }
}
