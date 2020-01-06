<?php

namespace MageSuite\OrderExport\Test\Integration\Service\Export\File;

class WriterFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderExport\Service\Writer\WriterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->factory = $this->objectManager->get(\MageSuite\OrderExport\Service\Writer\WriterFactory::class);
    }

    public function testItImplementsWriterFactoryInterface()
    {
        $this->assertInstanceOf(\MageSuite\OrderExport\Service\Writer\WriterFactory::class, $this->factory);
    }

    public function testItReturnsNullWhenWriterDoesNotExists()
    {
        $createdWriters = $this->factory->create('not_existing_writer');

        $this->assertNull($createdWriters);
    }

    public function getWriters()
    {
        return [
            ['csv', \MageSuite\OrderExport\Service\File\Adapter\CSVWriter::class],
        ];
    }
}
