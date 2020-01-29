<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get(\Magento\Framework\Registry::class);
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$orderCollection = $objectManager->create(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
foreach ($orderCollection as $order) {
    $order->delete();
}

$productCollection = $objectManager->create(\Magento\Catalog\Model\ResourceModel\Product\Collection::class);
foreach ($productCollection as $product) {
    $product->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
