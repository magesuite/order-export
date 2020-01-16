<?php

namespace MageSuite\OrderExport\Block\Adminhtml\ExportLog;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->removeButton('back')->removeButton('reset');
        $this->updateButton('save', 'label', 'Generate');

        $this->_objectId = 'orderexport_id';
        $this->_blockGroup = 'MageSuite_OrderExport';
        $this->_controller = 'adminhtml_exportlog';
    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Order Export');
    }
}
