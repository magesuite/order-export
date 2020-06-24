<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\Order;

class MassExport extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'MageSuite_OrderExport::order_export_action';

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \MageSuite\OrderExport\Service\ExporterSelectedFactory
     */
    protected $exporterSelectedFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \MageSuite\OrderExport\Service\ExporterSelectedFactory $exporterSelectedFactory
    )
    {
        parent::__construct($context);

        $this->messageManager = $messageManager;
        $this->exporterSelectedFactory = $exporterSelectedFactory;
    }

    public function execute()
    {
        $postData = $this->_request->getPost();
        $orderIds = $postData['selected'] ?? [];
        $data = [
            'type' => \MageSuite\OrderExport\Helper\Configuration::MANUAL_EXPORT_TYPE,
            'order_ids' => $orderIds
        ];

        $exporter = $this->exporterSelectedFactory->create(['data' => $data]);
        $result = $exporter->execute();

        $this->messageManager->addNoticeMessage(__('Export finished, %1 order(s) were exported.', $result['exportedCount']));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}