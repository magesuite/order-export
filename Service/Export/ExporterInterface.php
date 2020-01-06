<?php

namespace MageSuite\OrderExport\Service\Export;

interface ExporterInterface
{
    /**
     * @param array $orders
     * @param string $filePath
     */
    public function export($orders, $filePath = '');
}
