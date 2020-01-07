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
        $newStatus = $observer->getEvent()->getData('new_status');

        if (!$this->configuration->shouldChangeStatusAfterExport($newStatus)) {
            return;
        }

        $newStatus = $newStatus ?? $this->configuration->getNewStatus();

        if (empty($newStatus)) {
            return;
        }

        $type = $observer->getEvent()->getData('type');
        $message = $type ? sprintf('Order has been exported (%s)', $type) : 'Order has been exported';

        $orders = $observer->getEvent()->getData('orders');

        foreach ($orders as $order) {
            $order->addStatusToHistory($newStatus, __($message), \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $order->save();
        }
    }

    protected function shouldChangeStatusAfterExport($newStatus)
    {
        if (!empty($newStatus)) {
            return true;
        }

        if ($this->configuration->changeStatusAfterExport()) {
            return true;
        }

        return false;
    }
}
