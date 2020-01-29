<?php

namespace MageSuite\OrderExport\Block\Adminhtml\ExportLog;

class Show extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    public function getExportLog()
    {
        return $this->registry->registry('orderexport_exportlog');
    }
}
