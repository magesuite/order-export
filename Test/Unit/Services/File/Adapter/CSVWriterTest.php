<?php

namespace MageSuite\OrderExport\Test\Unit\Services\File;

use PHPUnit\Framework\TestCase;

class CSVWriterTest extends TestCase
{

    /**
     * @var \MageSuite\OrderExport\Services\File\Writer
     */
    protected $writer;

    public function setUp()
    {
        $this->writer = new \MageSuite\OrderExport\Services\File\Adapter\CSVWriter();
    }

    public function testItWritesToFileProperly()
    {
        $data = [
            'increment_id' => '1',
            'name' => 'firstname lastname',
            'telephone' => '11111111',
            'email' => 'customer@null.com',
            'company' => NULL,
            'address' => 'street 1',
            'city' => 'city',
            'postcode' => '11111',
            'region' => 'CA',
            'country' => 'US',
            'shipping_name' => 'firstname lastname',
            'shipping_company' => NULL,
            'shipping_address' => 'street 1',
            'shipping_city' => 'city',
            'shipping_postcode' => '11111',
            'shipping_region' => 'CA',
            'shipping_country' => 'US',
            'shipping_amount' => '5',
            'shipping_method' => 'flatrate_flatrate',
            'payment_method' => 'checkmo',
        ];
        $expectedLine = '1;"firstname lastname";11111111;customer@null.com;;"street 1";city;11111;CA;US;"firstname lastname";;"street 1";city;11111;CA;US;5;flatrate_flatrate;checkmo;;;;' . PHP_EOL;
        $this->writer->openFile(__DIR__ . '/write_test');
        $this->writer->write($data);
        $this->writer->closeFile();

        $this->assertEquals([$expectedLine], file(__DIR__ . '/write_test'));
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/write_test')) {
            return;
        }

        unlink(__DIR__ . '/write_test');
    }

}