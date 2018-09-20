<?php
namespace MageSuite\OrderExport\Test\Unit\Services\Export\Exporter\Strategy\XML;

use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class GroupedTest extends TestCase
{
    /** @var \MageSuite\OrderExport\Services\Export\Exporter\Strategy\XML\Grouped */
    protected $xmlExporter;

    /**
     * @var \MageSuite\OrderExport\Services\File\WriterFactory
     */
    protected $writerFactoryStub;

    /**
     * @var \MageSuite\OrderExport\Services\File\Adapter\XMLWriter
     */
    protected $XMLWriter;

    protected function setUp()
    {
        $this->XMLWriter = new \MageSuite\OrderExport\Services\File\Adapter\XMLWriter();
        $this->writerFactoryStub = $this
            ->getMockBuilder(\MageSuite\OrderExport\Services\File\WriterFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->xmlExporter = Bootstrap::getObjectManager()->create(
            'MageSuite\OrderExport\Services\Export\Exporter\Strategy\XML\Grouped',
            ['writerFactory' => $this->writerFactoryStub]
        );;
    }

    public function testOrderCollectionConverter()
    {
        $this->writerFactoryStub->method('create')->willReturn($this->XMLWriter);
        $data = [
            [
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
                        'product_id' => '50'
                    ]
                ]
            ],
            [
                'increment_id' => '100000002',
                'order' => [
                    'increment_id' => '100000002',
                    'prefix' => 'Mrs',
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
                    'shipping_prefix' => 'Mrs',
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
                    'customer_id' => '5'
                ],
                'items' => [
                    [
                        'increment_id' => '100000002',
                        'sku' => 'sku0',
                        'product_name' => 'TestName',
                        'quantity' => '2',
                        'price' => '10',
                        'product_id' => '72'
                    ]
                ]
            ]
        ];

        $exportDir = __DIR__ . '/../../../../assets/export_xml.xml';
        $expectedDir = __DIR__ . '/../../../../assets/expected_export_xml_grouped.xml';
        $expected = ['success' => 2, 'successIds' => ['100000001', '100000002'], 'ordersData'];

        $result = $this->xmlExporter->export($data, $exportDir);
        $this->assertEquals(file($expectedDir), file($exportDir));
        $this->assertEquals($expected['success'], $result['success']);
        $this->assertEquals($expected['success'], $result['success']);
        $this->assertEquals($expected['successIds'], $result['successIds']);
        $this->assertTrue(isset($result['ordersData']));
    }

    public function tearDown()
    {
        if (!file_exists(__DIR__ . '/../../../assets/export_xml.xml')) {
            return;
        }

        unlink(__DIR__ . '/../../../assets/export_xml.xml');
    }
}