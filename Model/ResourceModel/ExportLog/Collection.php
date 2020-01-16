<?php
namespace MageSuite\OrderExport\Model\ResourceModel\ExportLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\MageSuite\OrderExport\Model\Data\ExportLog::class, \MageSuite\OrderExport\Model\ResourceModel\ExportLog::class);
    }
}
