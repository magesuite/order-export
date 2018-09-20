<?php

namespace MageSuite\OrderExport\Services\Export\Exporter;

use MageSuite\OrderExport\Services\Export\Exporter;

class XML implements Exporter
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \MageSuite\OrderExport\Services\Export\Strategy\Mapper\ExportMapperFactory
     */
    protected $exportMapperFactory;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MageSuite\OrderExport\Services\Export\Strategy\Mapper\ExportMapperFactory $exportMapperFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->exportMapperFactory = $exportMapperFactory;
    }

    /**
     * @param array $orders
     * @param string $filePath
     * @return array
     */
    public function export($orders, $filePath = '')
    {
        $storeConfig = $this->scopeConfig;
        $exportStrategy = $storeConfig->getValue('orderexport/automatic/export_strategy', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);;
        $mappedExportStrategy = $this->exportMapperFactory->create('xml', $exportStrategy);

        $exportResult = $mappedExportStrategy->export($orders, $filePath);

        return $exportResult;
    }
}