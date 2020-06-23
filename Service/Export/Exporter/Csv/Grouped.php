<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Csv;

class Grouped extends \MageSuite\OrderExport\Service\Export\Exporter implements \MageSuite\OrderExport\Service\Export\ExporterInterface
{
    public function export($orders)
    {
        $writer = $this->writerFactory->create();

        $convertedOrders = [];
        foreach ($orders as $key => $order) {
            $convertedOrders[$key] = $this->convertOrder($order);
        }

        $filename = $this->fileNameGenerator->getFileName();
        $filePath = $this->getFilePath($filename);

        $writer->openFile($filePath);
        $writer->write($convertedOrders);
        $writer->closeFile();

        return [
            'exportedCount' => count($convertedOrders),
            'exportedIds' => array_column($convertedOrders, 'increment_id'),
            'fileName' => $filename,
            'generatedFiles' => [
                'fileName' => $filename,
                'filePath' => $filePath
            ]
        ];
    }
}
