<?php

namespace MageSuite\OrderExport\Services\File;


abstract class AbstractWriterFactory
{

    /**
     * @var array
     */
    protected $classMappings = [
        'csv' => \MageSuite\OrderExport\Services\File\Adapter\CSVWriter::class,
        'xml' => \MageSuite\OrderExport\Services\File\Adapter\XMLWriter::class
    ];

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $componentName
     * @return Writer
     */
    public function create(string $componentName)
    {
        if (!isset($this->classMappings[$componentName])) {
            return null;
        }

        return $this->objectManager->create($this->classMappings[$componentName]);
    }
}