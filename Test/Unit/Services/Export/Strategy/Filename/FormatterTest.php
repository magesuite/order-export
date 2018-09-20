<?php

namespace MageSuite\OrderExport\Test\Unit\Services\Export\Strategy\Filename;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    /**
     * @var \MageSuite\OrderExport\Services\Export\Strategy\Filename\Formatter
     */
    protected $formatter;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $storeConfig;


    protected function setUp()
    {
        $this->formatter = Bootstrap::getObjectManager()->create('MageSuite\OrderExport\Services\Export\Strategy\Filename\Formatter');
        $this->storeConfig = Bootstrap::getObjectManager()->create('\Magento\Framework\App\Config\ScopeConfigInterface');
    }

    public function testItReturnsCorrectExportFilename()
    {
        $date = new \DateTime();
        $dateFormat = $this->storeConfig->getValue('orderexport/automatic/export_date_format', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $exportType = $this->storeConfig->getValue('orderexport/automatic/export_file_type', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $filename = $this->formatter->getFilename('102933099', 'separated');

        $expected = 'order_102933099_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);

        $filename = $this->formatter->getFilename('102933099', 'grouped');

        $expected = 'order_increment_id_' . $date->format($dateFormat) . '.' . $exportType;
        $this->assertEquals($expected, $filename);
    }
}