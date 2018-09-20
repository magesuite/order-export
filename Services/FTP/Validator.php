<?php

namespace MageSuite\OrderExport\Services\FTP;

use FtpClient\FtpClient;

class Validator
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

    public function validate($exportResult)
    {
        $active = $this->scopeConfig->getValue('orderexport/automatic/ftp_upload', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$active) {
            return null;
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

        $directoryContent = $this->ftpClient->scanDir($path);

        $uploadedFiles = $this->getUploadedFilesFromDirectory($directoryContent);

        $validationResult = [];
        foreach ($exportResult['ordersData'] as $file) {
            if(isset($uploadedFiles[$file['filename']])){
                $validationResult['success'][] = $uploadedFiles[$file['filename']];
            } else {
                $validationResult['failure'][] = $file['filename'];
            }
        }
        $this->ftpClient->close();

        return $validationResult;
    }

    public function getUploadedFilesFromDirectory($directoryContent)
    {
        $parsedArray = [];
        foreach ($directoryContent as $fileData) {
            $parsedArray[$fileData['name']] = $fileData['name'] . ', ' . $fileData['time'] . ' ' . $fileData['day'] . ' ' . $fileData['month'] . ', ' . $fileData['size'] . 'B';
        }

        return $parsedArray;
    }
}