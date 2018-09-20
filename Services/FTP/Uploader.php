<?php

namespace MageSuite\OrderExport\Services\FTP;

use FtpClient\FtpClient;

class Uploader
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var FtpClient
     */
    private $ftpClient;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \FtpClient\FtpClient $ftpClient
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->ftpClient = $ftpClient;
    }

    public function upload($filePath)
    {
        $active = $this->scopeConfig->getValue('orderexport/automatic/ftp_upload', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$active) {
            return;
        }

        $host = $this->scopeConfig->getValue('orderexport/automatic/ftp_host', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $passive = $this->scopeConfig->getValue('orderexport/automatic/ftp_passive', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $login = $this->scopeConfig->getValue('orderexport/automatic/ftp_login', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $password = $this->scopeConfig->getValue('orderexport/automatic/ftp_password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $path = $this->scopeConfig->getValue('orderexport/automatic/ftp_path', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $ssl = $this->scopeConfig->getValue('orderexport/automatic/ftp_ssl', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $sslFlag = $ssl ? true : false;
        $this->ftpClient->connect($host, $sslFlag);
        $this->ftpClient->login($login, $password);
        if ($passive) {
            $this->ftpClient->pasv(true);
        }

        $remoteFilePath = $path . basename($filePath);

        $this->ftpClient->put($remoteFilePath, $filePath, FTP_ASCII);
        $this->ftpClient->close();
    }
}