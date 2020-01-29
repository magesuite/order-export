<?php
namespace MageSuite\OrderExport\Model\Data;

class ExportLog extends \Magento\Framework\Model\AbstractModel implements \MageSuite\OrderExport\Api\Data\ExportLogInterface
{
    protected function _construct()
    {
        $this->_init(\MageSuite\OrderExport\Model\ResourceModel\ExportLog::class);
    }

    public function setExportId($exportId)
    {
        return $this->setData('export_id', $exportId);
    }

    public function getExportId()
    {
        return $this->getData('export_id');
    }

    public function setType($type)
    {
        return $this->setData('type', $type);
    }

    public function getType()
    {
        return $this->getData('type');
    }

    public function setExportedFilename($exportedFilename)
    {
        return $this->setData('exported_filename', $exportedFilename);
    }

    public function getExportedFilename()
    {
        return $this->getData('exported_filename');
    }

    public function setExportedCount($exportedCount)
    {
        return $this->setData('exported_count', $exportedCount);
    }

    public function getExportedCount()
    {
        return $this->getData('exported_count');
    }

    public function setExportedIds($exportedIds)
    {
        return $this->setData('exported_ids', $exportedIds);
    }

    public function getExportedIds()
    {
        return $this->getData('exported_ids');
    }

    public function setStartedAt($startedAt)
    {
        return $this->setData('started_at', $startedAt);
    }

    public function getStartedAt()
    {
        return $this->getData('started_at');
    }

    public function setFinishedAt($finishedAt)
    {
        return $this->setData('finished_at', $finishedAt);
    }

    public function getFinishedAt()
    {
        return $this->getData('finished_at');
    }

    public function setUsedOrderFilters($usedOrderFilters)
    {
        return $this->setData('used_order_filters', $usedOrderFilters);
    }

    public function getUsedOrderFilters()
    {
        return $this->getData('used_order_filters');
    }

    public function setUploadedFilesStatus($uploadedFilesStatus)
    {
        return $this->setData('uploaded_files_status', $uploadedFilesStatus);
    }

    public function getUploadedFilesStatus()
    {
        return $this->getData('uploaded_files_status');
    }
}
