<?php

namespace MageSuite\OrderExport\Api\Data;

interface ExportStatusInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getOrderId();

    /**
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * @return string
     */
    public function getIncrementId();

    /**
     * @param string $incrementId
     * @return $this
     */
    public function setIncrementId($incrementId);

    /**
     * @return string
     */
    public function getStatusData();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatusData($status);
}
