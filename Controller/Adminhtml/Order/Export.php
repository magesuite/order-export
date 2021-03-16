<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\Order;

class Export extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'MageSuite_OrderExport::order_export_action';

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\OrderExport\Service\SelectedOrdersExporterFactory
     */
    protected $selectedOrdersExporterFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        \MageSuite\OrderExport\Service\SelectedOrdersExporterFactory $selectedOrdersExporterFactory
    )
    {
        parent::__construct($context);

        $this->messageManager = $messageManager;
        $this->configuration = $configuration;
        $this->selectedOrdersExporterFactory = $selectedOrdersExporterFactory;
    }

    public function execute()
    {
        if (!$this->configuration->isExportFromOrderGridEnabled()) {
            $this->messageManager->addNoticeMessage(__('This feature is disabled. Please enable Export from Order Grid in Stores > Configuration > MageSuite > OrderExport'));
            return $this->_redirect($this->_redirect->getRefererUrl());
        }

        $orderId = $this->_request->getParam('order_id');
        $data = [
            'type' => \MageSuite\OrderExport\Helper\Configuration::MANUAL_EXPORT_TYPE,
            'order_ids' => [$orderId],
            'allowed_statuses' => $this->configuration->getAllowedOrderStatuses()
        ];

        $exporter = $this->selectedOrdersExporterFactory->create(['data' => $data]);
        $result = $exporter->execute();

        $this->messageManager->addNoticeMessage(__('Export finished, %1 order was exported.', $result['exportedCount']));

        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}