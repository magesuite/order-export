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

    public function setSuccess($success)
    {
        return $this->setData('success', $success);
    }

    public function getSuccess()
    {
        return $this->getData('success');
    }

    public function setSuccessIds($successIds)
    {
        return $this->setData('success_ids', $successIds);
    }

    public function getSuccessIds()
    {
        return $this->getData('success_ids');
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

    public function setSearchOrderStatus($searchOrderStatus)
    {
        return $this->setData('search_order_status', $searchOrderStatus);
    }

    public function getSearchOrderStatus()
    {
        return $this->getData('search_order_status');
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
