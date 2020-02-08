<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Csv;

class Grouped extends \MageSuite\OrderExport\Service\Export\Exporter implements \MageSuite\OrderExport\Service\Export\ExporterInterface
{
    public function export($orders)
    {
        $writer = $this->writerFactory->create();

        $filename = $this->fileNameGenerator->getFileName();
        $filePath = $this->getFilePath($filename);

        $writer->openFile($filePath);
        $writer->writeHeader();

        $exportResult = ['exportedCount' => 0, 'exportedIds' => [], 'generatedFiles' => []];

        foreach ($orders as $order) {
            $order = $this->convertOrder($order);
            $writer->write($order['order']);

            foreach ($order['items'] as $item) {
                $writer->write($item);
            }

            $exportResult['exportedCount'] += 1;
            $exportResult['exportedIds'][] = $order['increment_id'];
        }

        $writer->closeFile();

        $exportResult['generatedFiles'][] = [
            'fileName' => $filename,
            'filePath' => $filePath
        ];

        return $exportResult;
    }
}
