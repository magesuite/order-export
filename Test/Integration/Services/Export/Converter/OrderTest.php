<?php

namespace MageSuite\OrderExport\Test\Integration\Services\Export\Converter;

use MageSuite\OrderExport\Services\Export\Converter\Order as OrderConverter;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class OrderTest extends TestCase
{
    /** @var OrderConverter */
    protected $orderConverter;

    protected function setUp()
    {
        $this->orderConverter = Bootstrap::getObjectManager()->create('MageSuite\OrderExport\Services\Export\Converter\Order');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     */
    public function testOrderConverter()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = Bootstrap::getObjectManager()->create('Magento\Sales\Model\Order');
        $order->loadByIncrementId('100000001');
        $result = $this->orderConverter->toArray($order);
        $expectedSize = 1;
        $expectedOrderData = [
            'increment_id' => $order->getIncrementId(),
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
            'shipping_amount' => '0.00',
            'shipping_method' => 'Flat Rate',
            'payment_method' => 'checkmo',
            'store_id' => '1',
            'customer_id' => ''
        ];

        $this->assertEquals('100000001', $result['increment_id']);

        foreach($expectedOrderData as $key => $value) {
            $this->assertEquals($value, $result['order'][$key]);
        }
        
        $this->assertEquals($expectedSize, count($result['items']));
    }

    public static function loadOrders()
    {
        include __DIR__ . '/../../../../_files/order.php';
    }
}