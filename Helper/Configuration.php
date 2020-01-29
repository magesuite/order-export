<?php

namespace MageSuite\OrderExport\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ORDER_EXPORT_PERIODICAL_CONFIG = 'orderexport/periodical';

    const CRON_EXPORT_TYPE = 'cron';
    const MANUAL_EXPORT_TYPE = 'manual';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    protected $periodicalExportConfig = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfigInterface;
        $this->directoryList = $directoryList;
    }

    public function isPeriodicalExportEnabled()
    {
        return (bool)$this->getPeriodicalExportConfig()->getIsEnabled();
    }

    public function logAllExports()
    {
        return (bool)$this->getPeriodicalExportConfig()->getLogAllExports();
    }

    public function shouldChangeStatusAfterExport()
    {
        return (bool)$this->getPeriodicalExportConfig()->getChangeStatusAfterExport();
    }

    public function getStatusAfterExport()
    {
        return $this->getPeriodicalExportConfig()->getStatusAfterExport();
    }

    public function shouldExportOrdersDaily()
    {
        return (bool)$this->getPeriodicalExportConfig()->getExportOrdersDaily();
    }

    public function getExportStrategy()
    {
        return $this->getPeriodicalExportConfig()->getExportStrategy();
    }

    public function getExportFileType()
    {
        return $this->getPeriodicalExportConfig()->getExportFileType();
    }

    public function getExportFilename()
    {
        return $this->getPeriodicalExportConfig()->getExportFilename();
    }

    public function getExportDateFormat()
    {
        return $this->getPeriodicalExportConfig()->getExportDateFormat();
    }

    public function getUploadPath()
    {
        $uploadPath = $this->getPeriodicalExportConfig()->getUploadPath();

        return rtrim(sprintf('%s/%s', $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR), $uploadPath), '/');
    }

    public function isFtpUploadEnabled()
    {
        return (bool)$this->getPeriodicalExportConfig()->getIsFtpUploadEnabled();
    }

    public function getFtpConfig()
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

    protected function getPeriodicalExportConfig()
    {
        if ($this->periodicalExportConfig === null) {
            $this->periodicalExportConfig = new \Magento\Framework\DataObject(
                $this->scopeConfig->getValue(self::XML_PATH_ORDER_EXPORT_PERIODICAL_CONFIG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            );
        }

        return $this->periodicalExportConfig;
    }
}
