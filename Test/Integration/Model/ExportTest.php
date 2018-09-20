<?php

namespace MageSuite\OrderExport\Test\Integration\Model;


use PHPUnit\Framework\TestCase;

class ExportTest extends TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
    }

    public function testSetResult()
    {
        $importModel = $this->objectManager->create(\MageSuite\OrderExport\Model\Export::class);

        $result = ['success' => 1, 'successIds' => ['100000001']];

        $importModel->setResult('manual', 'export.csv', 'all', $result);
        $this->assertEquals('manual', $importModel->getType());
        $this->assertEquals('export.csv', $importModel->getExportedFilename());
        $this->assertEquals('all', $importModel->getSearchOrderStatus());
        $this->assertEquals('1', $importModel->getSuccess());
        $this->assertEquals('100000001', $importModel->getSuccessIds());
        $this->assertInstanceOf('DateTime', $importModel->getFinishedAt());
    }

}