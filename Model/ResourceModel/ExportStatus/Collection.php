<?php
namespace MageSuite\OrderExport\Model\ResourceModel\ExportStatus;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\MageSuite\OrderExport\Model\Data\ExportStatus::class, \MageSuite\OrderExport\Model\ResourceModel\ExportStatus::class);
    }
}
