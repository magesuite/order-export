<?php
namespace MageSuite\OrderExport\Service\Export;

class Exporter implements \MageSuite\OrderExport\Service\Export\ExporterInterface
{
    /**
     * @var \MageSuite\OrderExport\Service\Writer\WriterFactory
     */
    protected $writerFactory;

    /**
     * @var \MageSuite\OrderExport\Service\FilenameInterface
     */
    protected $filename;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\OrderExport\Service\Converter\OrderInterface
     */
    protected $orderConverter;

    protected $writer;

    public function __construct(
        \MageSuite\OrderExport\Service\Writer\WriterFactory $writerFactory,
        \MageSuite\OrderExport\Service\FilenameInterface $filename,
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        \MageSuite\OrderExport\Service\Converter\OrderInterface $orderConverter
    ) {
        $this->writerFactory = $writerFactory;
        $this->filename = $filename;
        $this->configuration = $configuration;
        $this->orderConverter = $orderConverter;
    }

    public function export($orders)
    {
        $writer = $this->writerFactory->create();
    }

    protected function convertOrder($order)
    {
        return $this->orderConverter->convert($order);
    }

    protected function getFilePath($filename)
    {
        return sprintf('%s/%s', $this->configuration->getUploadPath(), $filename);
    }
}