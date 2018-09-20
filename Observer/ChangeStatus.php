<?php

namespace MageSuite\OrderExport\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

class ChangeStatus implements ObserverInterface
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $changeStatus = $this->scopeConfig->getValue('orderexport/automatic/change_status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $newStatus = $this->scopeConfig->getValue('orderexport/automatic/new_status', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($changeStatus) {
            $orders = $observer->getEvent()->getData('orders');
            /** @var Order $order */
            foreach ($orders as $order) {
                $order->addStatusToHistory($newStatus, 'Order has been exported', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);;
                $order->save();
            }
        }
    }
}