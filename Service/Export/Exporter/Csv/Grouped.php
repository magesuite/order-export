<?php
namespace MageSuite\OrderExport\Service\Export\Exporter\Csv;

class Grouped extends \MageSuite\OrderExport\Service\Export\Exporter
{
    public function export($orders, $filePath = '')
    {
        $writer = $this->writerFactory->create();

        $exportResult = ['success' => 0, 'successIds' => [], 'ordersData' => []];

        $filename = $this->formatter->getFilename();

        if ($filePath == '') {
            $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . '/orderexport/' . $filename;
        }

        $writer->openFile($filePath);
        $writer->writeHeader();

        foreach ($orders as $order) {
            $writer->write($order['order']);
            foreach ($order['items'] as $item) {
                $writer->write($item);
            }

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
