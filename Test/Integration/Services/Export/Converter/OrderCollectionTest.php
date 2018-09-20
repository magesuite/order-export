<?php

namespace MageSuite\OrderExport\Test\Integration\Services\Export\Converter;

use MageSuite\OrderExport\Services\Export\Converter\OrderCollection as OrderCollectionConverter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class OrderCollectionTest extends TestCase
{
    /** @var OrderCollectionConverter */
    protected $orderCollectionConverter;

    /** @var OrderRepositoryInterface */
    protected $repository;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    protected function setUp()
    {
        $this->orderCollectionConverter = Bootstrap::getObjectManager()->create('MageSuite\OrderExport\Services\Export\Converter\OrderCollection');
        $this->repository = Bootstrap::getObjectManager()->create('Magento\Sales\Api\OrderRepositoryInterface');
        $this->searchCriteriaBuilder = Bootstrap::getObjectManager()->create('Magento\Framework\Api\SearchCriteriaBuilder');
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadOrders
     */
    public function testOrderCollectionConverter()
    {
        $this->markTestSkipped();
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $orders = $this->repository->getList($searchCriteria)->getItems();

        $result = $this->orderCollectionConverter->toArray($orders);
        $expectedSize = 1;

        $this->assertEquals($expectedSize, count($result));
    }

    public static function loadOrders()
    {
        include __DIR__ . '/../../../../_files/order.php';
    }
}