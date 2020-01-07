<?php

namespace MageSuite\OrderExport\Test\Unit\Service;

class FilenameTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\OrderExport\Service\Filename
     */
    protected $filename;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;


    protected function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->filename = $objectManager->create(\MageSuite\OrderExport\Service\Filename::class);
        $this->configuration = $objectManager->create(\MageSuite\OrderExport\Helper\Configuration::class);
    }

    public function testItReturnsCorrectExportFilename()
    {
        $date = new \DateTime();

        $dateFormat = $this->configuration->getExportDateFormat();
        $exportType = $this->configuration->getExportFileType();

        $filename = $this->filename->getFilename('102933099', 'separated');

        $expected = 'order_102933099_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);

        $filename = $this->filename->getFilename('102933099', 'grouped');

        $expected = 'order_increment_id_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);
    }
}