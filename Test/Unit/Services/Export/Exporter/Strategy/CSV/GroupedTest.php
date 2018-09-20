<?php
namespace MageSuite\OrderExport\Test\Unit\Services\Export\Exporter\Strategy\CSV;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class GroupedTest extends TestCase
{
    /** @var \MageSuite\OrderExport\Services\Export\Exporter\Strategy\CSV\Grouped */
    protected $csvExporter;

    /**
     * @var \MageSuite\OrderExport\Services\File\WriterFactory
     */
    protected $writerFactoryStub;

    /**
     * @var \MageSuite\OrderExport\Services\File\Adapter\CSVWriter
     */
    protected $CSVWriter;

    protected function setUp()
    {
        $this->CSVWriter = new \MageSuite\OrderExport\Services\File\Adapter\CSVWriter();
        $this->writerFactoryStub = $this
            ->getMockBuilder(\MageSuite\OrderExport\Services\File\WriterFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->csvExporter = Bootstrap::getObjectManager()->create(
            'MageSuite\OrderExport\Services\Export\Exporter\Strategy\CSV\Grouped',
            ['writerFactory' => $this->writerFactoryStub]
        );;
    }

    public function testOrderCollectionConverter()
    {
        $this->writerFactoryStub->method('create')->willReturn($this->CSVWriter);
        $data = [
            [
                'increment_id' => '100000001',
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
                    ],
                    [
                        'increment_id' => '100000001',
                        'sku' => 'sku1',
                        'product_name' => 'TestName2',
                        'quantity' => '2',
                        'price' => '10'
                    ]
                ]
            ],
            [
                'increment_id' => '100000002',
                'order' => [
                    'increment_id' => '100000002',
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
                        'increment_id' => '100000002',
                        'sku' => 'sku0',
                        'product_name' => 'TestName',
                        'quantity' => '2',
                        'price' => '10'
                    ]
                ]
            ]
        ];

        $exportDir = __DIR__ . '/../../../../assets/export.csv';
        $expectedDir = __DIR__ . '/../../../../assets/expected_export_grouped.csv';
        $expected = ['success' => 2, 'successIds' => ['100000001', '100000002'], 'ordersData'];

        $result = $this->csvExporter->export($data, $exportDir);
        $this->assertEquals(file($expectedDir), file($exportDir));
        $this->assertEquals($expected['success'], $result['success']);
        $this->assertEquals($expected['success'], $result['success']);
        $this->assertEquals($expected['successIds'], $result['successIds']);
        $this->assertTrue(isset($result['ordersData']));
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/../../../assets/export.csv')) {
            return;
        }

        unlink(__DIR__ . '/../../../assets/export.csv');
    }
}