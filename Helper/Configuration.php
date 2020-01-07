<?php

namespace MageSuite\OrderExport\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ORDER_EXPORT_AUTOMATIC_CONFIG = 'orderexport/automatic';

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

    protected $exportAutomaticConfig = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfigInterface;
        $this->directoryList = $directoryList;
    }

    public function isAutomaticExportEnabled()
    {
        return (bool)$this->getExportAutomaticConfig()->getIsEnabled();
    }

    public function logAllExports()
    {
        return (bool)$this->getExportAutomaticConfig()->getLogAllExports();
    }

    public function shouldChangeStatusAfterExport()
    {
        return (bool)$this->getExportAutomaticConfig()->getChangeStatusAfterExport();
    }

    public function getNewStatus()
    {
        return $this->getExportAutomaticConfig()->getNewStatus();
    }

    public function shouldExportOrdersDaily()
    {
        return (bool)$this->getExportAutomaticConfig()->getExportOrdersDaily();
    }

    public function getExportStrategy()
    {
        return $this->getExportAutomaticConfig()->getExportStrategy();
    }

    public function getExportFileType()
    {
        return $this->getExportAutomaticConfig()->getExportFileType();
    }

    public function getExportFilename()
    {
        return $this->getExportAutomaticConfig()->getExportFilename();
    }

    public function getExportDateFormat()
    {
        return $this->getExportAutomaticConfig()->getExportDateFormat();
    }

    public function getUploadPath()
    {
        $uploadPath = $this->getExportAutomaticConfig()->getUploadPath();

        return rtrim(sprintf('%s/%s', $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR), $uploadPath), '/');
    }

    public function isFtpUploadEnabled()
    {
        return (bool)$this->getExportAutomaticConfig()->getIsFtpUploadEnabled();
    }

    public function getFtpConfig()
    {
        $config = $this->getExportAutomaticConfig();

        return new \Magento\Framework\DataObject([
            'host' => $config->getFtpHost(),
            'ssl_flag' => (bool)$config->getFtpSsl(),
            'is_passive' => (bool)$config->getFtpPassive(),
            'login' => $config->getFtpLogin(),
            'password' => $config->getFtpPassword(),
            'path' => $config->getFtpPath()
        ]);
    }

    protected function getExportAutomaticConfig()
    {
        if ($this->exportAutomaticConfig === null) {
            $this->exportAutomaticConfig = new \Magento\Framework\DataObject(
                $this->scopeConfig->getValue(self::XML_PATH_ORDER_EXPORT_AUTOMATIC_CONFIG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
            );
        }

        return $this->exportAutomaticConfig;
    }
}
