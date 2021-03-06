<?php
namespace MageSuite\OrderExport\Services\Export\Exporter\Strategy\XML;

use MageSuite\OrderExport\Services\File\Adapter\CSVWriter;

class Grouped implements \MageSuite\OrderExport\Services\Export\Exporter
{
    /**
     * @var \MageSuite\OrderExport\Services\File\WriterFactory
     */
    private $writerFactory;

    /**
     * @var \MageSuite\OrderExport\Services\Export\Strategy\Filename\Formatter
     */
    protected $formatter;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var CSVWriter
     */
    private $writer;

    public function __construct(
        \MageSuite\OrderExport\Services\File\WriterFactory $writerFactory,
        \MageSuite\OrderExport\Services\Export\Strategy\Filename\Formatter $formatter,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    )
    {
        $this->writerFactory = $writerFactory;
        $this->formatter = $formatter;
        $this->directoryList = $directoryList;
    }

    /**
     * @param array $orders
     * @param string $filePath
     * @return array
     */
    public function export($orders, $filePath = '')
    {
        $this->writer = $this->writerFactory->create('xml');

        $exportResult = ['success' => 0, 'successIds' => [], 'ordersData' => []];
        $filename = $this->formatter->getFilename();
        if ($filePath == '') {
            $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR) . '/orderexport/' . $filename;
        }
        $this->writer->openFile($filePath);
        $orders['strategy'] = 'grouped';
        $this->writer->write($orders);
        unset($orders['strategy']);
        foreach ($orders as $order) {
            $exportResult['success'] += 1;
            $exportResult['successIds'][] = $order['increment_id'];
        }
        $exportResult['ordersData'][] = [
            'filename' => $filename,
            'filepath' => $filePath
        ];

        $this->writer->closeFile();

        return $exportResult;
    }

}