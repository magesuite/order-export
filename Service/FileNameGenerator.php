<?php

namespace MageSuite\OrderExport\Service;

class FileNameGenerator implements \MageSuite\OrderExport\Service\FileNameGeneratorInterface
{
    protected \MageSuite\OrderExport\Helper\Configuration $configuration;

    public function __construct(\MageSuite\OrderExport\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getFileName($incrementId = null, $strategyType = null, $entityId = null)
    {
        $strategyType = $strategyType ?? $this->configuration->getExportStrategy();

        switch ($strategyType) {
            case 'grouped':
                $filename = $this->getGroupedFilename();
                break;
            case 'separated':
                $filename = $this->getSeparatedFilename((string)$incrementId, (string)$entityId);
                break;
            default:
                $filename = 'order_export';
                break;
        }

        return $this->addFileExtension($filename);
    }

    protected function getGroupedFilename(): string
    {
        $filename = str_replace('%entity_id%', 'entity_id', $this->configuration->getExportFilename());
        $filename = str_replace('%increment_id%', 'increment_id', $filename);
        $filename = str_replace('%export_date%', $this->getExportDate(), $filename);

        return $filename;
    }

    protected function getSeparatedFilename(string $incrementId, string $entityId): string
    {
        $filename = str_replace('%entity_id%', $entityId, $this->configuration->getExportFilename());
        $filename = str_replace('%increment_id%', $incrementId, $filename);
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
        return sprintf('%s.%s', $fileName, $this->configuration->getExportFileType());
    }
}
