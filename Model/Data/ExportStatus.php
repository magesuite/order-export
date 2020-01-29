<?php
namespace MageSuite\OrderExport\Model\Data;

class ExportStatus extends \Magento\Catalog\Model\AbstractModel implements \MageSuite\OrderExport\Api\Data\ExportStatusInterface
{
    protected function _construct()
    {
        $this->_init(\MageSuite\OrderExport\Model\ResourceModel\ExportStatus::class);
    }

    public function getId()
    {
        return $this->getData('id');
    }

    public function setId($id)
    {
        $this->setData('id', $id);

        return $this;
    }

    public function getOrderId()
    {
        return $this->getData('order_id');
    }

    public function setOrderId($orderId)
    {
        $this->setData('order_id', $orderId);

        return $this;
    }

    public function getIncrementId()
    {
        return $this->getData('increment_id');
    }

    public function setIncrementId($incrementId)
    {
        $this->setData('increment_id', $incrementId);

        return $this;
    }

    public function getStatusData()
    {
        return $this->getData('status');
    }

    public function setStatusData($status)
    {
        $this->setData('status', $status);

        return $this;
    }
}
