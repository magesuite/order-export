<?php
namespace MageSuite\OrderExport\Service\Export;

class Exporter implements \MageSuite\OrderExport\Service\Export\ExporterInterface
{
    /**
     * @var \MageSuite\OrderExport\Service\Writer\WriterFactory
     */
    protected $writerFactory;

    /**
     * @var \MageSuite\OrderExport\Service\Filename\Formatter
     */
    protected $formatter;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    protected $writer;

    public function __construct(
        \MageSuite\OrderExport\Service\Writer\WriterFactory $writerFactory,
        \MageSuite\OrderExport\Service\Filename\Formatter $formatter,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        $this->writerFactory = $writerFactory;
        $this->formatter = $formatter;
        $this->directoryList = $directoryList;
    }

    public function export($orders, $filePath = '')
    {
        $writer = $this->writerFactory->create();
    }
}