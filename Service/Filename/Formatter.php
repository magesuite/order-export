<?php

namespace MageSuite\OrderExport\Service\Filename;

class Formatter implements \MageSuite\OrderExport\Service\Filename\FormatterInterface
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\OrderExport\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getFilename($incrementId = null, $strategyType = null, $storeId = null)
    {
        $strategyType = $strategyType ?? $this->configuration->getExportStrategy();

        switch ($strategyType) {
            case 'grouped':
                $filename = $this->getGroupedFilename();
                break;
            case 'separated':
                $filename = $this->getSeparatedFilename($incrementId);
                break;
            default:
                $filename = 'order_export';
                break;
        }

        return $this->addFileExtension($filename);
    }

    protected function getGroupedFilename()
    {
        $filename = str_replace('%increment_id%', 'increment_id', $this->configuration->getExportFilename());
        $filename = str_replace('%export_date%', $this->getExportDate(), $filename);

        return $filename;
    }

    protected function getSeparatedFilename($incrementId)
    {
        $filename = str_replace('%increment_id%', $incrementId, $this->configuration->getExportFilename());
        $filename = str_replace('%export_date%', $this->getExportDate(), $filename);

        return $filename;
    }

    protected function getExportDate()
    {
        $date = new \DateTime();

        return $date->format($this->configuration->getExportDateFormat());
    }

    protected function addFileExtension($fileName)
    {
        return sprintf('%s.%s', $fileName, $this->configuration->getExportStrategy());
    }
}
