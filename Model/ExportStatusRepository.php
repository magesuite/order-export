<?php
namespace MageSuite\OrderExport\Model;

class ExportStatusRepository implements \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface
{
    /**
     * @var ResourceModel\ExportStatus\CollectionFactory
     */
    protected $statusCollectionFactory;
    /**
     * @var \MageSuite\OrderExport\Api\Data\ExportStatusInterfaceFactory
     */
    protected $exportStatusFactory;
    /**
     * @var ResourceModel\ExportStatus
     */
    protected $exportStatusResource;
    /**
     * @var \MageSuite\OrderExport\Model\OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var \Magento\Framework\Webapi\Rest\Response
     */
    protected $apiResponse;

    public function __construct(
        \MageSuite\OrderExport\Model\ResourceModel\ExportStatus\CollectionFactory $statusCollectionFactory,
        \MageSuite\OrderExport\Api\Data\ExportStatusInterfaceFactory $exportStatusFactory,
        \MageSuite\OrderExport\Model\ResourceModel\ExportStatus $exportStatusResource,
        \MageSuite\OrderExport\Model\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Webapi\Rest\Response $apiResponse
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->exportStatusFactory = $exportStatusFactory;
        $this->exportStatusResource = $exportStatusResource;
        $this->orderRepository = $orderRepository;
        $this->apiResponse = $apiResponse;
    }

    public function addStatus($order, $stepData)
    {
        $exportStatus = $this->getById($order->getId());

        if (!$exportStatus) {
            $exportStatus = $this->exportStatusFactory->create();
            $exportStatus
                ->setOrderId($order->getId())
                ->setIncrementId($order->getIncrementId());
        }

        $savedStepData = [];

        if ($exportStatus->getStatusData()) {
            $savedStepData = json_decode($exportStatus->getStatusData(), true);
        }

        $savedStepData[$stepData['status']] = $stepData;

        $exportStatus->setStatusData(json_encode($savedStepData));

        $this->exportStatusResource->save($exportStatus);

        return $exportStatus;
    }

    public function getById($orderId)
    {
        $collection = $this->statusCollectionFactory->create();

        $collection->addFieldToFilter('order_id', ['eq' => $orderId]);

        if (!$collection->getSize()) {
            return null;
        }

        return $collection->getFirstItem();
    }

    public function addStatusByApi($incrementId, $status)
    {
        $order = $this->getByIncrementId($incrementId);

        if (!$order) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Order does not exist.'));
        }

        $status = [
            'status' => $status
        ];

        return $this->addStatus($order, $status);
    }

    protected function getByIncrementId($incrementId)
    {
        $filters = [
            ['field' => 'increment_id', 'value' => $incrementId, 'condition' => 'eq']
        ];

        $orders = $this->orderRepository->getOrdersList($filters);

        if (empty($orders)) {
            return null;
        }

        return $orders->getFirstItem();
    }
}
