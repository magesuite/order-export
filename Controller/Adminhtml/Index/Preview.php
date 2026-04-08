<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Controller\Adminhtml\Index;

class Preview extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'MageSuite_OrderExport::config_orderexport';

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        protected \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        protected \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        protected \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\App\ResponseInterface
    {
        try {
            $fileName = (string)$this->getRequest()->getParam('file_name');
            // phpcs:ignore Magento2.Functions.DiscouragedFunction
            $fileName = basename($fileName);

            if (empty($fileName)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Invalid file name.')
                );
            }

            return $this->fileFactory->create(
                $fileName,
                [
                    'type' => 'filename',
                    'value' => 'orderexport/' . $fileName
                ],
                \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                'application/octet-stream',
                ''
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Unable to preview the file.'));
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
    }
}
