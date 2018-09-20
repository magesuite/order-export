<?php

namespace MageSuite\OrderExport\Observer;

use MageSuite\OrderExport\Services\FTP\Uploader;
use Magento\Framework\Event\ObserverInterface;

class ValidateOrderExport implements ObserverInterface
{


    /**
     * @var \MageSuite\OrderExport\Services\FTP\Validator
     */
    private $validator;
    /**
     * @var \MageSuite\OrderExport\Repository\ExportRepository
     */
    private $exportRepository;

    public function __construct(
        \MageSuite\OrderExport\Services\FTP\Validator $validator,
        \MageSuite\OrderExport\Repository\ExportRepository $exportRepository
    )
    {
        $this->validator = $validator;
        $this->exportRepository = $exportRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $result = $observer->getEvent()->getData('result');
        $export = $observer->getEvent()->getData('export');

        $validationResult = $this->validator->validate($result);

        $validationResultText = $this->resultToText($validationResult);

        $export->setUploadedStatus($validationResultText);

        $this->exportRepository->save($export);
    }

    public function resultToText($validationResult)
    {
        if (empty($validationResult)) {
            return "Upload disabled.";
        }

        $text = 'Success:' . "\n";
        if (isset($validationResult['success'])) {
            foreach ($validationResult['success'] as $row) {
                $text .= $row . "\n";
            }
        } else {
            $text .= "No files found." . "\n";
        }

        if (isset($validationResult['failure'])) {
            $text .= 'Failure:' . "\n";
            foreach ($validationResult['failure'] as $row) {
                $text .= $row . "\n";
            }
        }

        return $text;
    }
}