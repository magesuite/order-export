<?php

namespace MageSuite\OrderExport\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ORDER_EXPORT_AUTOMATIC_CONFIG = 'orderexport/automatic';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    protected $exportAutomaticConfig = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfigInterface;
    }

    public function isAutomaticExportEnabled()
    {
        return (bool)$this->getExportAutomaticConfig()->getIsEnabled();
    }

    public function changeStatusAfterExport()
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

    public function isFtpUploadEnabled()
    {
        return $this->getExportAutomaticConfig()->getIsFtpUploadEnabled();
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
