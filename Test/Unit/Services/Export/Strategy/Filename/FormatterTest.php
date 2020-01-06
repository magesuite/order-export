<?php

namespace MageSuite\OrderExport\Test\Unit\Service\Export\Strategy\Filename;

class FormatterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\OrderExport\Service\Filename\Formatter
     */
    protected $formatter;

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;


    protected function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->formatter = $objectManager->create(\MageSuite\OrderExport\Service\Filename\Formatter::class);
        $this->configuration = $objectManager->create(\MageSuite\OrderExport\Helper\Configuration::class);
    }

    public function testItReturnsCorrectExportFilename()
    {
        $date = new \DateTime();

        $dateFormat = $this->configuration->getExportDateFormat();
        $exportType = $this->configuration->getExportFileType();

        $filename = $this->formatter->getFilename('102933099', 'separated');

        $expected = 'order_102933099_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);

        $filename = $this->formatter->getFilename('102933099', 'grouped');

        $expected = 'order_increment_id_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);
    }
}