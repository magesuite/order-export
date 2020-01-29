<?php

namespace MageSuite\OrderExport\Observer;

class ChangeStatusAfterExport implements \Magento\Framework\Event\ObserverInterface
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
        $statusAfterExport = $observer->getEvent()->getData('status_after_export');

        if (!$this->shouldChangeStatusAfterExport($statusAfterExport)) {
            return;
        }

        $statusAfterExport = $statusAfterExport ?? $this->configuration->getStatusAfterExport();

        if (empty($statusAfterExport)) {
            return;
        }

        $type = $observer->getEvent()->getData('type');
        $message = $type ? sprintf('Order has been exported (%s)', $type) : 'Order has been exported';

        $orders = $observer->getEvent()->getData('orders');

        foreach ($orders as $order) {
            $order->addStatusToHistory($statusAfterExport, $message, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $order->save();
        }
    }

    protected function shouldChangeStatusAfterExport($statusAfterExport)
    {
        if (!empty($statusAfterExport)) {
            return true;
        }

        if ($this->configuration->shouldChangeStatusAfterExport()) {
            return true;
        }

        return false;
    }
}
