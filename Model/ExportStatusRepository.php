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
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;
    /**
     * @var \Magento\Framework\Webapi\Rest\Response
     */
    protected $apiResponse;

    public function __construct(
        \MageSuite\OrderExport\Model\ResourceModel\ExportStatus\CollectionFactory $statusCollectionFactory,
        \MageSuite\OrderExport\Api\Data\ExportStatusInterfaceFactory $exportStatusFactory,
        \MageSuite\OrderExport\Model\ResourceModel\ExportStatus $exportStatusResource,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Framework\Webapi\Rest\Response $apiResponse
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
        $this->exportStatusFactory = $exportStatusFactory;
        $this->exportStatusResource = $exportStatusResource;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->apiResponse = $apiResponse;
    }

    public function addStatus($order, $stepData)
    {
        $erpStatus = $this->getOrderData($order->getId());

        if (!$erpStatus) {
            $erpStatus = $this->exportStatusFactory->create();
            $erpStatus
                ->setOrderId($order->getId())
                ->setIncrementId($order->getIncrementId());
        }

        $savedStepData = [];

        if ($erpStatus->getStatusData()) {
            $savedStepData = json_decode($erpStatus->getStatusData(), true);
        }

        $savedStepData[$stepData['status']] = $stepData;

        $erpStatus->setStatusData(json_encode($savedStepData));

        $this->exportStatusResource->save($erpStatus);

        return $erpStatus;
    }

    public function getOrderData($orderId)
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
        $order = $this->getOrder($incrementId);

        if (!$order) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Order does not exist.'));
        }

        $status = [
            'status' => $status
        ];

        return $this->addStatus($order, $status);
    }

    protected function getOrder($incrementId)
    {
        $collection = $this->orderCollectionFactory->create();

        $collection->addFieldToFilter('increment_id', ['eq' => $incrementId]);

        if (!$collection->getSize()) {
            return null;
        }

        return $collection->getFirstItem();
    }
}
