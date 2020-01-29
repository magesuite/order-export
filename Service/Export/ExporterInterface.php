<?php

namespace MageSuite\OrderExport\Service\Export;

interface ExporterInterface
{
    /**
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterface $orders
     */
    public function export($orders);
}
