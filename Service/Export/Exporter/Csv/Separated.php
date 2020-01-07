<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Csv;

class Separated extends \MageSuite\OrderExport\Service\Export\Exporter
{
    public function export($orders)
    {
        $writer = $this->writerFactory->create();

        $exportResult = ['success' => 0, 'successIds' => [], 'ordersData' => []];
        $orderUploadData = [];
        foreach ($orders as $order) {
            $order = $this->convertOrder($order);

            $storeId = $order['order']['store_id'] ?? null;

            $filename = $this->filename->getFilename($order['increment_id'], null, $storeId);
            $filePath = $this->getFilePath($filename);

            $writer->openFile($filePath);
            $writer->writeHeader();
            $writer->write($order['order']);
            foreach ($order['items'] as $item) {
                $writer->write($item);
            }
            $exportResult['success'] += 1;
            $exportResult['successIds'][] = $order['increment_id'];
            $orderUploadData[] = [
                'filename' => $filename,
                'filepath' => $filePath
            ];
            $exportResult['filename'] = $filename;
            $exportResult['filepath'] = $filePath;
            $writer->closeFile();
        }
        $exportResult['ordersData'] = $orderUploadData;
        return $exportResult;
    }
}
