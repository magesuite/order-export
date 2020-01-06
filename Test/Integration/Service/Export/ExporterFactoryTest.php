<?php

namespace MageSuite\OrderExport\Test\Integration\Service\Export;

class ExporterFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderExport\Service\Export\ExporterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    public function setUp() {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->factory = $this->objectManager->get(\MageSuite\OrderExport\Service\Export\ExporterFactory::class);
    }

    public function testItImplementsExporterFactoryInterface() {
        $this->assertInstanceOf(\MageSuite\OrderExport\Service\Export\ExporterFactory::class, $this->factory);
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
            ['csv', \MageSuite\OrderExport\Service\Export\Exporter\CSV::class],
            ['xml', \MageSuite\OrderExport\Service\Export\Exporter\XML::class],
        ];
    }
}