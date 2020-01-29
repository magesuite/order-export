<?php
namespace MageSuite\OrderExport\Model\Config\Source\Export;

class Strategy implements \Magento\Framework\Option\ArrayInterface
{
    const EXPORT_STRATEGY_GROUPED = 'grouped';
    const EXPORT_STRATEGY_SEPARATED = 'separated';

    public function toOptionArray()
    {
        return [
            self::EXPORT_STRATEGY_SEPARATED => __('Separated'),
            self::EXPORT_STRATEGY_GROUPED => __('Grouped')
        ];
    }
}