<?php

namespace MageSuite\OrderExport\Service\Export;

interface ExporterInterface
{
    /**
     * @param array $orders
     */
    public function export($orders);
}
