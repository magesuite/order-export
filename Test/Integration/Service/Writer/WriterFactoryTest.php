<?php

namespace MageSuite\OrderExport\Test\Integration\Service\Export\Writer;

class WriterFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderExport\Service\Writer\WriterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $factory;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->factory = $this->objectManager->get(\MageSuite\OrderExport\Service\Writer\WriterFactory::class);
    }

    public function testItImplementsWriterFactoryInterface()
    {
        $this->assertInstanceOf(\MageSuite\OrderExport\Service\Writer\WriterFactory::class, $this->factory);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store orderexport/automatic/export_file_type non_existing
     */
    public function testItReturnsNullWhenWriterDoesNotExists()
    {
        $createdWriters = $this->factory->create();

        $this->assertNull($createdWriters);
    }
}
