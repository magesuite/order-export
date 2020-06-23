<?php

namespace MageSuite\OrderExport\Test\Unit\Service\Writer;

class CsvTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\OrderExport\Service\Writer\WriterInterface
     */
    protected $writer;

    public function setUp()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->writer = $objectManager->create(\MageSuite\OrderExport\Service\Writer\Csv::class);
    }

    public function testItWritesToFileProperly()
    {
        $this->markTestSkipped('Need to change logic');

        $expectedResult = file_get_contents(__DIR__ . '/assets/expected_write_result.csv');
        $orderData = $this->getOrderData();

        $this->writer->openFile(__DIR__ . '/write_test.csv');
        $this->writer->write($orderData);
        $this->writer->closeFile();

        $writeResult = file_get_contents(__DIR__ . '/write_test.csv');

        $this->assertEquals($expectedResult, $writeResult);
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/write_test.csv')) {
            return;
        }

        unlink(__DIR__ . '/write_test.csv');
    }

    protected function getOrderData()
    {
        $data = [
            'increment_id' => '1',
            'order' => [
                'increment_id' => '1',
                'payment_method' => 'checkmo',
                'created_at' => '2020-12-12 12:12:12',
                'customer_id' => '1',
                'shipping_method' => 'Flatrate',
                'shipping_amount' => 5,
                'billing' => [
                    'prefix' => 'Sir',
                    'name' => 'firstname lastname',
                    'first_name' => 'firstname',
                    'last_name' => 'lastname',
                    'email' => 'customer@null.com',
                    'company' => null,
                    'street' => 'street 1',
                    'city' => 'city',
                    'postcode' => '11111',
                    'region' => 'CA',
                    'country' => 'US',
                    'telephone' => '123456789'
                ],
                'shipping' => [
                    'prefix' => 'Sir',
                    'name' => 'firstname lastname',
                    'first_name' => 'firstname',
                    'last_name' => 'lastname',
                    'email' => 'customer@null.com',
                    'company' => null,
                    'street' => 'street 1',
                    'city' => 'city',
                    'postcode' => '11111',
                    'region' => 'CA',
                    'country' => 'US',
                    'telephone' => '123456789'
                ]
            ],
            'items' => [
                [
                    'product_id' => 1,
                    'sku' => 'sku1',
                    'name' => 'Product Name 1',
                    'qty_ordered' => 10,
                    'price_incl_tax' => 9.99
                ],
                [
                    'product_id' => 2,
                    'sku' => 'sku2',
                    'name' => 'Product Name 2',
                    'qty_ordered' => 5,
                    'price_incl_tax' => 5.45
                ]
            ]
        ];

        return [$data];
    }
}
