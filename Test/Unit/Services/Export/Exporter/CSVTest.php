<?php

namespace MageSuite\OrderExport\Test\Unit\Services\Export\Exporter;

use MageSuite\OrderExport\Services\Export\Exporter\CSV as CSVExporter;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class CSVTest extends TestCase
{
    /** @var CSVExporter */
    protected $csvExporter;


    protected function setUp()
    {
        $this->csvExporter = Bootstrap::getObjectManager()->create('MageSuite\OrderExport\Services\Export\Exporter\CSV');
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testOrderCollectionConverter()
    {
        $expected = ['success' => 1, 'successIds' => ['100000001'], 'ordersData'];
        $data = [['increment_id' => '100000001',
            'order' => [
                'increment_id' => '100000001',
                'name' => 'firstname lastname',
                'telephone' => '11111111',
                'email' => 'customer@null.com',
                'company' => NULL,
                'address' => 'street',
                'city' => 'Los Angeles',
                'postcode' => '11111',
                'region' => 'CA',
                'country' => 'US',
                'shipping_name' => 'firstname lastname',
                'shipping_company' => NULL,
                'shipping_address' => 'street',
                'shipping_city' => 'Los Angeles',
                'shipping_postcode' => '11111',
                'shipping_region' => 'CA',
                'shipping_country' => 'US',
                'shipping_amount' => '5',
                'shipping_method' => 'flatrate_flatrate',
                'payment_method' => 'checkmo',
            ],
            'items' => [
                [
                    'increment_id' => '100000001',
                    'sku' => 'sku0',
                    'product_name' => 'TestName',
                    'quantity' => '2',
                    'price' => '10'
                ], [
                    'increment_id' => '100000001',
                    'sku' => 'sku1',
                    'product_name' => 'TestName2',
                    'quantity' => '2',
                    'price' => '10'
                ]
            ]]];
        $result = $this->csvExporter->export($data, __DIR__ . '/../../assets/export.csv');
        $this->assertEquals($expected['success'], $result['success']);
        $this->assertEquals($expected['successIds'], $result['successIds']);
        $this->assertTrue(isset($result['ordersData']));
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/../../assets/export.csv')) {
            return;
        }

        unlink(__DIR__ . '/../../assets/export.csv');
    }
}