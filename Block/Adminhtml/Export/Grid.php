<?php

namespace MageSuite\OrderExport\Block\Adminhtml\Export;

class Grid extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_orderexport';
        $this->_blockGroup = 'MageSuite_OrderExport';

        parent::_construct();

        $this->_headerText = __('Orders Export Logs');
        $this->removeButton('add');
    }

}