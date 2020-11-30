<?php

namespace MageSuite\OrderExport\Test\Unit\Service;

class FileNameGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\OrderExport\Service\FileNameGenerator
     */
    protected $fileNameGenerator;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->fileNameGenerator = $objectManager->create(\MageSuite\OrderExport\Service\FileNameGenerator::class);
        $this->configuration = $objectManager->create(\MageSuite\OrderExport\Helper\Configuration::class);
    }

    public function testItReturnsCorrectExportFilename()
    {
        $date = new \DateTime();

        $dateFormat = $this->configuration->getExportDateFormat();
        $exportType = $this->configuration->getExportFileType();

        $filename = $this->fileNameGenerator->getFileName('102933099', 'separated', '1001');

        $expected = 'order_102933099_1001_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);

        $filename = $this->fileNameGenerator->getFileName('102933099', 'grouped');

        $expected = 'order_increment_id_entity_id_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);
    }
}
