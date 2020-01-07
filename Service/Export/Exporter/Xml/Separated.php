<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Xml;

class Separated extends \MageSuite\OrderExport\Service\Export\Exporter
{
    public function export($orders, $filePath = '')
    {
        $writer = $this->writerFactory->create();

        $exportResult = ['success' => 0, 'successIds' => [], 'ordersData' => []];
        $orderUploadData = [];
        foreach ($orders as $order) {
            $order = $this->convertOrder($order);
            $storeId = $order['order']['store_id'] ?? null;
            $filename = $this->filename->getFilename($order['order']['increment_id'], null, $storeId);
            $filePath = $this->getFilePath($filename);

            $order['strategy'] = 'separated';
            $writer->openFile($filePath);

            $writer->write($order);

            $exportResult['success'] += 1;
            $exportResult['successIds'][] = $order['order']['increment_id'];
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
