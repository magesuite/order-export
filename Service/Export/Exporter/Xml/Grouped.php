<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Xml;

class Grouped extends \MageSuite\OrderExport\Service\Export\Exporter implements \MageSuite\OrderExport\Service\Export\ExporterInterface
{
    public function export($orders)
    {
        $writer = $this->writerFactory->create();

        $convertedOrders = [];
        $errors = [];

        foreach ($orders as $orderId => $order) {
            try {
                $convertedOrders[$orderId] = $this->convertOrder($order);
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
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
            'errors' => $errors,
            'generatedFiles' => [
                'fileName' => $filename,
                'filePath' => $filePath
            ]
        ];
    }
}
