<?php
namespace MageSuite\OrderExport\Test\Integration\Api;


class ExportStatusRepositoryInterfaceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface
     */
    protected $exportStatusRepository;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orderCollection;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->exportStatusRepository = $this->objectManager->create(\MageSuite\OrderExport\Api\ExportStatusRepositoryInterface::class);
        $this->orderCollection = $this->objectManager->create(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     */
    public function testItAddStatusCorrectly()
    {
        $orderCollection = $this->orderCollection;

        $orderCollection->addFieldToFilter('increment_id', ['eq' => '100000001']);

        $order = $orderCollection->getFirstItem();

        $this->exportStatusRepository->addStatus($order, $this->getStatusData());

        $exportStatusData = $this->exportStatusRepository->getOrderData($order->getId());

        $this->assertEquals($order->getId(), $exportStatusData->getOrderId());
        $this->assertEquals($order->getIncrementId(), $exportStatusData->getIncrementId());
        $this->assertEquals('{"ready_to_generate":{"status":"ready_to_generate","label":"Order ready to generate","completed":true}}', $exportStatusData->getStatusData());
    }

    private function getStatusData()
    {
        return [
            'status' => 'ready_to_generate',
            'label' => 'Order ready to generate',
            'completed' => true
        ];
    }

    public static function loadOrders()
    {
        include __DIR__ . '/../../_files/order.php';
    }
}
