<?php

namespace MageSuite\OrderExport\Service\FTP;

class Uploader
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

    public function upload($filePath)
    {
        if (!$this->configuration->isFtpUploadEnabled()) {
            return;
        }

        $ftpConfig = $this->configuration->getFtpConfig();

        $this->ftpClient->connect($ftpConfig->getHost(), $ftpConfig->getSslFlag());
        $this->ftpClient->login($ftpConfig->getLogin(), $ftpConfig->getPassword());

        if ($ftpConfig->getIsPassive()) {
            $this->ftpClient->pasv(true);
        }

        $remoteFilePath = $ftpConfig->getPath() . basename($filePath);

        $this->ftpClient->put($remoteFilePath, $filePath, FTP_ASCII);
        $this->ftpClient->close();
    }
}
