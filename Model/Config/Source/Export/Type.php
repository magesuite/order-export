<?php
namespace MageSuite\OrderExport\Model\Config\Source\Export;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Get file type
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            'csv' => 'csv',
            'xml' => 'xml'
        ];
    }
}
