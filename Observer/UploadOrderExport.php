<?php

namespace MageSuite\OrderExport\Observer;

use MageSuite\OrderExport\Services\FTP\Uploader;
use Magento\Framework\Event\ObserverInterface;

class UploadOrderExport implements ObserverInterface
{

    /**
     * @var Uploader
     */
    private $uploader;

    public function __construct(
        \MageSuite\OrderExport\Services\FTP\Uploader $uploader
    )
    {
        $this->uploader = $uploader;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $filePath = $observer->getEvent()->getData('filePath');
        $this->uploader->upload($filePath);
    }
}