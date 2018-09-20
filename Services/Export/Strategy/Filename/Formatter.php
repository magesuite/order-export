<?php

namespace MageSuite\OrderExport\Services\Export\Strategy\Filename;

class Formatter implements \MageSuite\OrderExport\Services\Export\Strategy\FilenameFormatter
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getFilename($incrementId = null, $strategyType = null, $storeId = null)
    {
        $configuration = $this->scopeConfig->getValue(self::EXPORT_CONFIGURATION_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        if (empty($strategyType)) {
            $strategyType = $configuration['export_strategy'];
        }

        $configFilename = $configuration['export_filename'];
        $exportType = $configuration['export_file_type'];
        $dateFormat = $configuration['export_date_format'];

        switch ($strategyType) {
            case 'grouped':
                $filename = $this->getGroupedFilename($configFilename, $dateFormat);
                break;
            case 'separated':
                $filename = $this->getSeparatedFilename($incrementId, $configFilename, $dateFormat);
                break;
            default:
                $filename = 'order_export';
                break;
        }

        return $this->addFileExtension($filename, $exportType);
    }

    protected function getGroupedFilename($configFilename, $dateFormat)
    {
        $filename = str_replace('%increment_id%', 'increment_id', $configFilename);
        $filename = str_replace('%export_date%', $this->getExportDate($dateFormat), $filename);

        return $filename;
    }

    protected function getSeparatedFilename($incrementId, $configFilename, $dateFormat)
    {
        $filename = str_replace('%increment_id%', $incrementId, $configFilename);
        $filename = str_replace('%export_date%', $this->getExportDate($dateFormat), $filename);

        return $filename;
    }

    protected function getExportDate($dateFormat)
    {
        $date = new \DateTime();

        return $date->format($dateFormat);
    }

    protected function addFileExtension($fileName, $exportType)
    {
        return $fileName . '.' . $exportType;
    }

}