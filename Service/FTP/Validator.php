<?php

namespace MageSuite\OrderExport\Service\FTP;

class Validator
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \FtpClient\FtpClient
     */
    protected $ftpClient;

    public function __construct(
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        \FtpClient\FtpClient $ftpClient
    ) {
        $this->configuration = $configuration;
        $this->ftpClient = $ftpClient;
    }

    public function validate($exportResult)
    {
        if (!$this->configuration->isFtpUploadEnabled()) {
            return null;
        }

        $ftpConfig = $this->configuration->getFtpConfig();

        $this->ftpClient->connect($ftpConfig->getHost(), $ftpConfig->getSslFlag());
        $this->ftpClient->login($ftpConfig->getLogin(), $ftpConfig->getPassword());

        if ($ftpConfig->getIsPassive()) {
            $this->ftpClient->pasv(true);
        }

        $directoryContent = $this->ftpClient->scanDir($ftpConfig->getPath());

        $uploadedFiles = $this->getUploadedFilesFromDirectory($directoryContent);

        $validationResult = [];

        foreach ($exportResult['ordersData'] as $file) {
            if (isset($uploadedFiles[$file['filename']])) {
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
            $filePath = sprintf(
                '%s, %s %s %s, %sB',
                $fileData['name'],
                $fileData['time'],
                $fileData['day'],
                $fileData['month'],
                $fileData['size']
            );

            $parsedArray[$fileData['name']] =  $filePath;
        }

        return $parsedArray;
    }
}
