<?php

namespace MageSuite\OrderExport\Block\Adminhtml\Order\View;

class ExportButton extends \Magento\Sales\Block\Adminhtml\Order\View
{

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\ConfigInterface $salesConfig,
        \Magento\Sales\Helper\Reorder $reorderHelper,
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $salesConfig, $reorderHelper, $data);

        $this->configuration = $configuration;
    }

    public function addExportButton()
    {
        if (!$this->configuration->isExportFromOrderGridEnabled()) {
            return $this;
        }

        $parentBlock = $this->getParentBlock();
        $buttonUrl = $this->_urlBuilder->getUrl(
            'orderexport/order/export',
            ['order_id' => $this->getParentBlock()->getOrderId()]
        );
        $message = __('Do you want to export this order?');

        $this->getToolbar()->addChild(
            'order_export_button',
            \Magento\Backend\Block\Widget\Button::class,
            ['label' => __('Order Export'), 'onclick' => "confirmSetLocation('{$message}', '{$buttonUrl}')"]
        );

        return $this;
    }
}