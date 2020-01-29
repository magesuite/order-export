<?php
namespace MageSuite\OrderExport\Service\Export;

class Exporter
{
    /**
     * @var \MageSuite\OrderExport\Service\Writer\WriterFactory
     */
    protected $writerFactory;

    /**
     * @var \MageSuite\OrderExport\Service\FileNameGeneratorInterface
     */
    protected $fileNameGenerator;

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
        \MageSuite\OrderExport\Service\FileNameGeneratorInterface $fileNameGenerator,
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        \MageSuite\OrderExport\Service\Converter\OrderInterface $orderConverter
    ) {
        $this->writerFactory = $writerFactory;
        $this->fileNameGenerator = $fileNameGenerator;
        $this->configuration = $configuration;
        $this->orderConverter = $orderConverter;
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