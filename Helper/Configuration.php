<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Helper;

class Configuration
{
    public const XML_PATH_ORDER_EXPORT_PERIODICAL_CONFIG = 'orderexport/periodical';
    public const XML_PATH_ORDER_EXPORT_ORDER_GRID_CONFIG = 'orderexport/order_grid';

    public const CRON_EXPORT_TYPE = 'cron';
    public const MANUAL_EXPORT_TYPE = 'manual';

    protected ?array $periodicalExportConfig = null;
    protected ?array $orderGridConfig = null;

    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        protected \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {}

    public function isPeriodicalExportEnabled(): bool
    {
        return (bool)$this->getPeriodicalExportConfig()->getIsEnabled();
    }

    public function logAllExports(): bool
    {
        return (bool)$this->getPeriodicalExportConfig()->getLogAllExports();
    }

    public function shouldChangeStatusAfterExport(): bool
    {
        return (bool)$this->getPeriodicalExportConfig()->getChangeStatusAfterExport();
    }

    public function getStatusAfterExport(): string
    {
        return $this->getPeriodicalExportConfig()->getStatusAfterExport();
    }

    public function shouldExportOrdersDaily(): bool
    {
        return (bool)$this->getPeriodicalExportConfig()->getExportOrdersDaily();
    }

    public function getExportStrategy(): string
    {
        return $this->getPeriodicalExportConfig()->getExportStrategy();
    }

    public function getExportFileType(): string
    {
        return $this->getPeriodicalExportConfig()->getExportFileType();
    }

    public function getExportFilename(?int $storeId = null): string
    {
        return $this->getPeriodicalExportConfig($storeId)->getExportFilename();
    }

    public function getExportDateFormat(?int $storeId = null): string
    {
        return $this->getPeriodicalExportConfig($storeId)->getExportDateFormat();
    }

    public function getUploadPath(?int $storeId = null): string
    {
        $uploadPath = $this->getPeriodicalExportConfig($storeId)->getUploadPath();

        return rtrim(sprintf('%s/%s', $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR), $uploadPath), '/');
    }

    public function isFtpUploadEnabled(): bool
    {
        return (bool)$this->getPeriodicalExportConfig()->getIsFtpUploadEnabled();
    }

    public function getFtpConfig(): \Magento\Framework\DataObject
    {
        $config = $this->getPeriodicalExportConfig();

        return new \Magento\Framework\DataObject([
            'host' => $config->getFtpHost(),
            'ssl_flag' => (bool)$config->getFtpSsl(),
            'is_passive' => (bool)$config->getFtpPassive(),
            'login' => $config->getFtpLogin(),
            'password' => $config->getFtpPassword(),
            'path' => $config->getFtpPath()
        ]);
    }

    protected function getPeriodicalExportConfig(?int $storeId = null): \Magento\Framework\DataObject
    {
        $key = $storeId ?? 'default';

        if (!isset($this->periodicalExportConfig[$key])) {
            $config = $this->scopeConfig->getValue(self::XML_PATH_ORDER_EXPORT_PERIODICAL_CONFIG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
            $this->periodicalExportConfig[$key] = new \Magento\Framework\DataObject($config);
        }

        return $this->periodicalExportConfig[$key];
    }

    public function isExportFromOrderGridEnabled(): bool
    {
        return (bool)$this->getOrderGridConfig()->getIsEnabled();
    }

    public function getAllowedOrderStatuses(): array
    {
        return explode(',', $this->getOrderGridConfig()->getAllowedOrderStatuses());
    }

    protected function getOrderGridConfig(?int $storeId = null): \Magento\Framework\DataObject
    {
        $key = $storeId ?? 'default';

        if (!isset($this->orderGridConfig[$key])) {
            $config = $this->scopeConfig->getValue(self::XML_PATH_ORDER_EXPORT_ORDER_GRID_CONFIG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
            $this->orderGridConfig[$key] = new \Magento\Framework\DataObject($config);
        }

        return $this->orderGridConfig[$key];
    }
}
