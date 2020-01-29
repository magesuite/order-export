<?php

namespace MageSuite\OrderExport\Observer;

class UploadOrderExport implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \MageSuite\OrderExport\Service\UploaderInterface
     */
    protected $uploader;

    public function __construct(
        \MageSuite\OrderExport\Service\UploaderInterface $uploader
    ) {
        $this->uploader = $uploader;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $result = $observer->getEvent()->getData('result');

        foreach ($result['generatedFiles'] as $file) {
            $this->uploader->upload($file['filePath']);
        }
    }
}
