<?php

namespace MageSuite\OrderExport\Test\Integration\Service\FTP;

class UploaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderExport\Service\FTP\Uploader
     */
    protected $uploader;

    /**
     * @var \FtpClient\FtpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $ftpClientDouble;

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
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store orderexport/periodical/is_ftp_upload_enabled 1
     * @magentoConfigFixture current_store orderexport/periodical/ftp_host ftp.example.com
     * @magentoConfigFixture current_store orderexport/periodical/ftp_passive 0
     * @magentoConfigFixture current_store orderexport/periodical/ftp_login user
     * @magentoConfigFixture current_store orderexport/periodical/ftp_password password
     * @magentoConfigFixture current_store orderexport/periodical/ftp_path /test/
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
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store orderexport/periodical/is_ftp_upload_enabled 0
     * @magentoConfigFixture current_store orderexport/periodical/ftp_host ftp.example.com
     * @magentoConfigFixture current_store orderexport/periodical/ftp_passive 1
     * @magentoConfigFixture current_store orderexport/periodical/ftp_login user
     * @magentoConfigFixture current_store orderexport/periodical/ftp_password password
     * @magentoConfigFixture current_store orderexport/periodical/ftp_path /test/
     */
    public function testDisabledUpload()
    {
        $filePath = '/directory/test.txt';

        $this->ftpClientDouble->expects($this->never())->method('connect');

        $this->uploader->upload($filePath);
    }
}