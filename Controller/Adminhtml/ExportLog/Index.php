<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\ExportLog;

class Index extends \Magento\Backend\App\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'MageSuite_OrderExport::config_orderexport';

    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Orders Export Logs'));

        return $resultPage;
    }
}
