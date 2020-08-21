<?php
namespace MageSuite\OrderExport\Model;

class ExportStatusRepository implements \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface
{

    /**
     * @var \MageSuite\OrderExport\Api\Data\ExportStatusInterfaceFactory
     */
    protected $exportStatusFactory;

    /**
     * @var \MageSuite\OrderExport\Model\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var ResourceModel\ExportStatus
     */
    protected $exportStatusResource;
    /**
     * @var ResourceModel\ExportStatus\CollectionFactory
     */
    protected $statusCollectionFactory;

    public function __construct(
        \MageSuite\OrderExport\Api\Data\ExportStatusInterfaceFactory $exportStatusFactory,
        \MageSuite\OrderExport\Model\OrderRepositoryInterface $orderRepository,
        \MageSuite\OrderExport\Model\ResourceModel\ExportStatus $exportStatusResource,
        \MageSuite\OrderExport\Model\ResourceModel\ExportStatus\CollectionFactory $statusCollectionFactory
    )
    {
        $this->exportStatusFactory = $exportStatusFactory;
        $this->orderRepository = $orderRepository;
        $this->exportStatusResource = $exportStatusResource;
        $this->statusCollectionFactory = $statusCollectionFactory;
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

    public function addStatusByOrderId($orderId, $status)
    {
        $order = $this->orderRepository->getById($orderId);

        if (!$order) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Order does not exist.'));
        }

        $status = [
            'status' => $status,
            'completed' => 1
        ];

        return $this->addStatus($order, $status);
    }

    public function addStatusByIncrementId($incrementId, $status)
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
