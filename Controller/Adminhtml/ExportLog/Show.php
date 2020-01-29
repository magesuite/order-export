<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\ExportLog;

class Show extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_OrderExport::config_orderexport';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \MageSuite\OrderExport\Api\ExportLogRepositoryInterface
     */
    protected $exportLogRepository;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \MageSuite\OrderExport\Api\ExportLogRepositoryInterface $exportLogRepository
    ) {
        parent::__construct($context);

        $this->registry = $registry;
        $this->exportLogRepository = $exportLogRepository;
    }

    public function execute()
    {
        $exportId = $this->getRequest()->getParam('export_id');

        try {
            $export = $this->exportLogRepository->getById($exportId);
            $this->registry->register('orderexport_exportlog', $export);

            $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
            $result->getConfig()->getTitle()->prepend(__('Orders Export Log'));

        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $result = $this->resultRedirectFactory->create();
            $this->messageManager->addErrorMessage(__('Item does not exist.'));
            $result->setPath('*/*/index');
        }

        return $result;
    }
}
