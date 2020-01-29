<?php
namespace MageSuite\OrderExport\Model\ResourceModel;

class ExportStatus extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('order_export_status', 'id');
    }
}
