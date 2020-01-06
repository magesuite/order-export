<?php

namespace MageSuite\OrderExport\Observer;

class ChangeStatus implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\OrderExport\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->configuration->changeStatusAfterExport()) {
            return;
        }

        $newStatus = $this->configuration->getNewStatus();
        $orders = $observer->getEvent()->getData('orders');

        foreach ($orders as $order) {
            $order->addStatusToHistory($newStatus, __('Order has been exported'), \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $order->save();
        }
    }
}
