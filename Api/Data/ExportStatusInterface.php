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
     * @return void
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getOrderId();

    /**
     * @param string $orderId
     * @return void
     */
    public function setOrderId($orderId);

    /**
     * @return string
     */
    public function getIncrementId();

    /**
     * @param string $incrementId
     * @return void
     */
    public function setIncrementId($incrementId);

    /**
     * @return string
     */
    public function getStatusData();

    /**
     * @param string $status
     * @return void
     */
    public function setStatusData($status);
}
