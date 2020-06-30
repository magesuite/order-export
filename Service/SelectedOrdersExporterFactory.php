<?php

namespace MageSuite\OrderExport\Service;

class SelectedOrdersExporterFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create($data = [])
    {
        return $this->objectManager->create(\MageSuite\OrderExport\Service\SelectedOrdersExporter::class, $data);
    }
}
