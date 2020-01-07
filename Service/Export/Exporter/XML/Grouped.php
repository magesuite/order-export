<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Xml;

class Grouped extends \MageSuite\OrderExport\Service\Export\Exporter
{
    public function export($orders)
    {
        $writer = $this->writerFactory->create();

        $exportResult = ['success' => 0, 'successIds' => [], 'ordersData' => []];
        $filename = $this->filename->getFilename();
        $filePath = $this->getFilePath($filename);

        $writer->openFile($filePath);
        $orders['strategy'] = 'grouped';
        $writer->write($orders);
        unset($orders['strategy']);
        foreach ($orders as $order) {
            $exportResult['success'] += 1;
            $exportResult['successIds'][] = $order['increment_id'];
        }
        $exportResult['ordersData'][] = [
            'filename' => $filename,
            'filepath' => $filePath
        ];

        $writer->closeFile();

        return $exportResult;
    }
}
