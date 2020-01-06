<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

/** @var \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface $exportStatusData */
$exportStatusData = $objectManager->create(\MageSuite\OrderExport\Api\ExportStatusRepositoryInterface::class);

/** @var \Magento\Sales\Model\ResourceModel\Order\Collection $orderCollection */
$orderCollection = $objectManager->create(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
;

$orderCollection->addFieldToFilter('increment_id', ['eq' => '100000001']);

$order = $orderCollection->getFirstItem();

$statuses = [
    [
        'status' => 'ready_to_generate',
        'label' => 'Order ready to generate',
        'completed' => true,
        'sort_order' => '100',
        'error' => 0,
        'error_message' => ''
    ],
    [
        'status' => 'file_generated',
        'label' => 'Order export file was generated',
        'completed' => true,
        'sort_order' => '200',
        'error' => 0,
        'error_message' => ''
    ],
    [
        'status' => 'file_exported',
        'label' => 'Order export file was exported to ERP',
        'completed' => true,
        'sort_order' => '300',
        'error' => 0,
        'error_message' => ''
    ]
];

foreach ($statuses as $status) {
    $exportStatusData->addStatus($order, $status);
}

/** @var \MageSuite\OrderExport\Api\Data\ExportInterface $exportModel */
$exportModel = $objectManager->create(\MageSuite\OrderExport\Api\Data\ExportInterface::class);

$result = ['success' => 1, 'successIds' => ['100000001']];

$exportModel
    ->setType('manual')
    ->setExportedFilename('export.csv')
    ->setSearchOrderStatus(null)
    ->setSuccess($result['success'])
    ->setSuccessIds(implode(', ', $result['successIds']))
    ->setFinishedAt(new \DateTime());

/** @var \MageSuite\OrderExport\Model\ResourceModel\Export $exportResourceModel */
$exportResourceModel = $objectManager->create(\MageSuite\OrderExport\Model\ResourceModel\Export::class);

$exportResourceModel->save($exportModel);
