<?php
namespace MageSuite\OrderExport\Model\ResourceModel\Export;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(\MageSuite\OrderExport\Model\Data\Export::class, \MageSuite\OrderExport\Model\ResourceModel\Export::class);
    }
}
