<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Csv;

class Separated extends \MageSuite\OrderExport\Service\Export\Exporter implements \MageSuite\OrderExport\Service\Export\ExporterInterface
{
    public function export($orders)
    {
        $writer = $this->writerFactory->create();

        $exportResult = ['exportedCount' => 0, 'exportedIds' => [], 'generatedFiles' => []];

        foreach ($orders as $order) {
            $order = $this->convertOrder($order);

            $filename = $this->fileNameGenerator->getFileName($order['increment_id'], null, $order['order']['entity_id']);
            $filePath = $this->getFilePath($filename);

            $writer->openFile($filePath);
            $writer->write([$order]);
            $writer->closeFile();

            $exportResult['exportedCount'] += 1;
            $exportResult['exportedIds'][] = $order['increment_id'];
            $exportResult['generatedFiles'][] = [
                'fileName' => $filename,
                'filePath' => $filePath
            ];
        }

        return $exportResult;
    }
}
