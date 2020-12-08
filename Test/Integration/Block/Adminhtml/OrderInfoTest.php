<?php
namespace MageSuite\OrderExport\Test\Integration\Block\Adminhtml;

class OrderInfoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orderCollection;

    /**
     * @var \MageSuite\OrderExport\Block\Adminhtml\OrderInfo
     */
    protected $orderInfoBlock;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->orderCollection = $this->objectManager->create(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
        $this->orderInfoBlock = $this->objectManager->create(\MageSuite\OrderExport\Block\Adminhtml\OrderInfo::class);
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     * @magentoDataFixture loadExportStatus
     */
    public function testItReturnOrderCorrectly()
    {
        $order = $this->getOrder();

        $this->registry->register('sales_order', $order);

        $orderInfoBlock = $this->orderInfoBlock->getOrder();

        $this->assertInstanceOf(\Magento\Sales\Model\Order::class, $orderInfoBlock);

        $this->registry->unregister('sales_order');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     * @magentoDataFixture loadExportStatus
     */
    public function testItCanShowBock()
    {
        $order = $this->getOrder();

        $this->registry->register('sales_order', $order);

        $this->assertTrue($this->orderInfoBlock->canShow());

        $this->registry->unregister('sales_order');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     * @magentoDataFixture loadExportStatus
     */
    public function testGetStatuses()
    {
        $order = $this->getOrder();

        $this->registry->register('sales_order', $order);

        $statuses = $this->orderInfoBlock->getStatuses();

        $this->assertArrayHasKey('ready_to_generate', $statuses);
        $this->assertArrayHasKey('file_generated', $statuses);
        $this->assertArrayHasKey('file_exported', $statuses);

        $this->registry->unregister('sales_order');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     * @magentoDataFixture loadExportStatus
     */
    public function testGetMatchedStatuses()
    {
        $order = $this->getOrder();

        $this->registry->register('sales_order', $order);

        $statuses = $this->orderInfoBlock->getMatchedStatuses();

        $expectedStatuses = $this->getMatchingStatuses();
        foreach ($statuses as $status) {
            $this->assertTrue(in_array($status['status'], $expectedStatuses));
        }

        $this->registry->unregister('sales_order');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     * @magentoDataFixture loadExportStatus
     */
    public function testGetExportUrl()
    {
        $order = $this->getOrder();

        $this->registry->register('sales_order', $order);

        $exportUrl = $this->orderInfoBlock->getExportLogUrl();

        $this->assertStringStartsWith('http://localhost/index.php/backend/orderexport/exportlog/show/export_id/', $exportUrl);

        $this->registry->unregister('sales_order');
    }

    public static function loadExportStatus()
    {
        include __DIR__ . '/../../../_files/export_status.php';
    }

    protected function getOrder()
    {
        $orderCollection = $this->orderCollection;

        $orderCollection->addFieldToFilter('increment_id', ['eq' => '100000001']);

        return $orderCollection->getFirstItem();
    }

    protected function getMatchingStatuses()
    {
        return [
            'ready_to_generate',
            'file_generated',
            'file_exported',
            'file_processed_by_erp',
            'payment_created',
            'order_shipped',
            'order_completed'
        ];
    }

    public static function loadOrders()
    {
        include __DIR__ . '/../../../_files/order.php';
    }
}
