<?php

namespace MageSuite\OrderExport\Model;

class Export extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_CRON = 'cron';
    const TYPE_MANUAL = 'manual';

    protected function _construct()
    {
        $this->_init('MageSuite\OrderExport\Model\ResourceModel\Export');
    }

    /**
     * @param string $type
     * @param string $filename
     * @param string $searchOrderStatus
     * @param array $result
     */
    public function setResult($type, $filename, $searchOrderStatus, $result)
    {
        $this->setType($type);
        $this->setExportedFilename($filename);
        $this->setSearchOrderStatus($searchOrderStatus);
        $this->setSuccess($result['success']);
        $this->setSuccessIds(implode(', ', $result['successIds']));
        $this->setFinishedAt(new \DateTime());
    }

    /**
     * @param string $status
     */
    public function setUploadedStatus($status)
    {
        $this->setUploadedFilesStatus($status);
    }
}