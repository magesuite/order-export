<?php

namespace MageSuite\OrderExport\Observer;

class ValidateOrderExport implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\OrderExport\Service\UploadValidatorInterface
     */
    protected $validator;

    /**
     * @var \MageSuite\OrderExport\Api\ExportLogRepositoryInterface
     */
    protected $exportLogRepository;

    public function __construct(
        \MageSuite\OrderExport\Service\UploadValidatorInterface $validator,
        \MageSuite\OrderExport\Api\ExportLogRepositoryInterface $exportLogRepository
    ) {
        $this->validator = $validator;
        $this->exportLogRepository = $exportLogRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $result = $observer->getEvent()->getData('result');
        $exportLog = $observer->getEvent()->getData('export_log');

        $validationResult = $this->validator->validate($result);

        $validationResultText = $this->resultToText($validationResult);

        $exportLog->setUploadedStatus($validationResultText);

        $this->exportLogRepository->save($exportLog);
    }

    public function resultToText($validationResult)
    {
        if (empty($validationResult)) {
            return 'Upload disabled.';
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
