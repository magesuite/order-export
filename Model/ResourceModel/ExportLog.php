<?php

namespace MageSuite\OrderExport\Model\ResourceModel;

class ExportLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('orderexport_log', 'export_id');
    }
}
