<?php

namespace MageSuite\OrderExport\Test\Integration\Services\Export\File;

use PHPUnit\Framework\TestCase;

class WriterFactoryTest extends TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\OrderExport\Services\File\WriterFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    public function setUp() {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->factory = $this->objectManager->get(\MageSuite\OrderExport\Services\File\WriterFactory::class);
    }

    public function testItImplementsWriterFactoryInterface() {
        $this->assertInstanceOf(\MageSuite\OrderExport\Services\File\WriterFactory::class, $this->factory);
    }

    public function testItReturnsNullWhenWriterDoesNotExists() {
        $createdWriters = $this->factory->create('not_existing_writer');

        $this->assertNull($createdWriters);
    }

    public function getWriters() {
        return [
            ['csv', \MageSuite\OrderExport\Services\File\Adapter\CSVWriter::class],
        ];
    }
}