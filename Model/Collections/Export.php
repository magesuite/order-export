<?php

namespace MageSuite\OrderExport\Model\Collections;

class Export extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    protected function _construct()
    {
        $this->_init('MageSuite\OrderExport\Model\Export','MageSuite\OrderExport\Model\ResourceModel\Export');
    }
}
