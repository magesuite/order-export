<?php

namespace MageSuite\OrderExport\Test\Integration\Service\Export;

class ExporterFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderExport\Service\Export\ExporterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $factory;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->factory = $this->objectManager->get(\MageSuite\OrderExport\Service\Export\ExporterFactory::class);
    }

    public function testItImplementsExporterFactoryInterface()
    {
        $this->assertInstanceOf(\MageSuite\OrderExport\Service\Export\ExporterFactory::class, $this->factory);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store orderexport/periodical/export_file_type non_existing
     */
    public function testItReturnsNullWhenExporterDoesNotExists()
    {
        $createdExporter = $this->factory->create();

        $this->assertNull($createdExporter);
    }
}
