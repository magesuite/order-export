<?php

namespace MageSuite\OrderExport\Observer;

class UploadOrderExport implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var \MageSuite\OrderExport\Service\FTP\Uploader
     */
    protected $uploader;

    public function __construct(
        \MageSuite\OrderExport\Service\FTP\Uploader $uploader
    ) {
        $this->uploader = $uploader;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $filePath = $observer->getEvent()->getData('filePath');
        $this->uploader->upload($filePath);
    }
}
