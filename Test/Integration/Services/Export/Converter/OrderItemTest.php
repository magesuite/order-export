<?php

namespace MageSuite\OrderExport\Test\Integration\Services\Export\Converter;

use MageSuite\OrderExport\Services\Export\Converter\OrderItem as OrderItemConverter;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class OrderItemTest extends TestCase
{
    /** @var OrderItemConverter */
    protected $orderItemConverter;

    protected function setUp()
    {
        $this->orderItemConverter = Bootstrap::getObjectManager()->create('MageSuite\OrderExport\Services\Export\Converter\OrderItem');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     */
    public function testOrderItemConverter()
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = Bootstrap::getObjectManager()->create('Magento\Sales\Model\Order');
        $order->loadByIncrementId('100000001');
        $orderItem = $order->getAllVisibleItems()[0];
        $orderItemConverter = Bootstrap::getObjectManager()->get('MageSuite\OrderExport\Services\Export\Converter\OrderItem');

        $result = $orderItemConverter->toArray($orderItem);
        $expected = [
            'order_id' => $order->getId(),
            'sku' => 'simple-export-test',
            'product_name' => NULL,
            'quantity' => 3,
            'price' => '10.00',
            'product_id' => '1'
        ];

        $this->assertEquals($expected, $result);
    }

    public static function loadOrders()
    {
        include __DIR__ . '/../../../../_files/order.php';
    }
}