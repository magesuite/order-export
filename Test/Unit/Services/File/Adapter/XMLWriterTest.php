<?php

namespace MageSuite\OrderExport\Test\Unit\Services\File;

use PHPUnit\Framework\TestCase;

class XMLWriterTest extends TestCase
{

    /**
     * @var \MageSuite\OrderExport\Services\File\Writer
     */
    protected $writer;

    public function setUp()
    {
        $this->writer = new \MageSuite\OrderExport\Services\File\Adapter\XMLWriter();
    }

    public function testItWritesToFileProperly()
    {
        $data = [
            'strategy' => 'separated',
            'increment_id' => '100000001',
            'order' => [
                'increment_id' => '100000001',
                'prefix' => 'Mr',
                'name' => 'firstname lastname',
                'first_name' => 'firstname',
                'last_name' => 'lastname',
                'telephone' => '11111111',
                'email' => 'customer@null.com',
                'company' => NULL,
                'address' => 'street',
                'city' => 'Los Angeles',
                'postcode' => '11111',
                'region' => 'CA',
                'country' => 'US',
                'shipping_prefix' => 'Mr',
                'shipping_name' => 'firstname lastname',
                'shipping_first_name' => 'firstname',
                'shipping_last_name' => 'lastname',
                'shipping_company' => NULL,
                'shipping_address' => 'street',
                'shipping_city' => 'Los Angeles',
                'shipping_postcode' => '11111',
                'shipping_region' => 'CA',
                'shipping_country' => 'US',
                'shipping_amount' => '5',
                'shipping_method' => 'flatrate_flatrate',
                'payment_method' => 'checkmo',
                'creation_date' => '2018-01-24 15:43:55',
                'store_id' => '1',
                'customer_id' => '1'
            ],
            'items' => [
                [
                    'increment_id' => '100000001',
                    'sku' => 'sku0',
                    'product_name' => 'TestName',
                    'quantity' => '2',
                    'price' => '10',
                    'product_id' => '34'
                ],
                [
                    'increment_id' => '100000001',
                    'sku' => 'sku1',
                    'product_name' => 'TestName2',
                    'quantity' => '2',
                    'price' => '10',
                    'product_id' => '34'
                ]
            ]
        ];
        $expected = new \SimpleXMLElement(file_get_contents(__DIR__ . '/assets/expected_write_result.xml'));

        $this->writer->openFile(__DIR__ . '/write_test.xml');
        $this->writer->write($data);
        $this->writer->closeFile();

        $writeResult = new \SimpleXMLElement(file_get_contents(__DIR__ . '/write_test.xml'));

        $this->assertEquals($expected->asXML(), $writeResult->asXML());
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/write_test.xml')) {
            return;
        }

        unlink(__DIR__ . '/write_test.xml');
    }

}