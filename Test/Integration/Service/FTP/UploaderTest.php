<?php

namespace MageSuite\OrderExport\Test\Integration\Service\FTP;

class UploaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderExport\Service\FTP\Uploader
     */
    private $uploader;

    /**
     * @var \FtpClient\FtpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $ftpClientDouble;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->ftpClientDouble = $this->createMock(\FtpClient\FtpClient::class);

        $this->uploader = $this
            ->objectManager
            ->create(
                \MageSuite\OrderExport\Service\FTP\Uploader::class,
                ['ftpClient' => $this->ftpClientDouble]
            );
    }

    /**
     * @magentoConfigFixture current_store orderexport/automatic/ftp_upload 1
     * @magentoConfigFixture current_store orderexport/automatic/ftp_host ftp.example.com
     * @magentoConfigFixture current_store orderexport/automatic/ftp_passive 0
     * @magentoConfigFixture current_store orderexport/automatic/ftp_login user
     * @magentoConfigFixture current_store orderexport/automatic/ftp_password password
     * @magentoConfigFixture current_store orderexport/automatic/ftp_path /test/
     */
    public function testEnabledUpload()
    {
        $filePath = '/directory/test.txt';

        $this->ftpClientDouble->expects($this->once())->method('connect')->with('ftp.example.com', true)->willReturnSelf();
        $this->ftpClientDouble->expects($this->once())->method('login')->with('user', 'password')->willReturnSelf();
        $this->ftpClientDouble->expects($this->once())->method('__call')->with('put', ['/test/test.txt', $filePath, FTP_ASCII]);
        $this->ftpClientDouble->expects($this->once())->method('close');

        $this->uploader->upload($filePath);
    }

    /**
     * @magentoConfigFixture current_store orderexport/automatic/ftp_upload 0
     * @magentoConfigFixture current_store orderexport/automatic/ftp_host ftp.example.com
     * @magentoConfigFixture current_store orderexport/automatic/ftp_passive 1
     * @magentoConfigFixture current_store orderexport/automatic/ftp_login user
     * @magentoConfigFixture current_store orderexport/automatic/ftp_password password
     * @magentoConfigFixture current_store orderexport/automatic/ftp_path /test/
     */
    public function testDisabledUpload()
    {
        $filePath = '/directory/test.txt';

        $this->ftpClientDouble->expects($this->never())->method('connect');

        $this->uploader->upload($filePath);
    }
}