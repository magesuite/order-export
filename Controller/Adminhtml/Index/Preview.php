<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\Index;

class Preview extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_OrderExport::config_orderexport';

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory = $fileFactory;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            $fileName = $this->getRequest()->getParam('file_name');
            $this->fileFactory->create(
                $fileName,
                [
                    'type' => 'filename',
                    'value' => 'orderexport/' . $fileName
                ],
                \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                'application/octet-stream',
                ''
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this->resultRawFactory->create();

    }
}