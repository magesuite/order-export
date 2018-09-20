<?php

namespace MageSuite\OrderExport\Test\Integration\Services\Export;

use PHPUnit\Framework\TestCase;

class ExporterFactoryTest extends TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderExport\Services\Export\ExporterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    public function setUp() {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->factory = $this->objectManager->get(\MageSuite\OrderExport\Services\Export\ExporterFactory::class);
    }

    public function testItImplementsExporterFactoryInterface() {
        $this->assertInstanceOf(\MageSuite\OrderExport\Services\Export\ExporterFactory::class, $this->factory);
    }

    public function testItReturnsNullWhenExporterDoesNotExists() {
        $createdExporter = $this->factory->create('not_existing_exporter');

        $this->assertNull($createdExporter);
    }
    /**
     * @dataProvider getExporter
     */
    public function testItReturnsExportersProperly($exporterType, $expectedClass) {

        $createdExporter = $this->factory->create($exporterType);

        $this->assertInstanceOf($expectedClass, $createdExporter);
    }

    public function getExporter() {
        return [
            ['csv', \MageSuite\OrderExport\Services\Export\Exporter\CSV::class],
            ['xml', \MageSuite\OrderExport\Services\Export\Exporter\XML::class],
        ];
    }
}