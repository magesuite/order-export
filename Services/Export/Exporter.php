<?php

namespace MageSuite\OrderExport\Services\Export;

interface Exporter
{
    /**
     * @param array $orders
     * @param string $filePath
     */
    public function export($orders, $filePath = '');
}