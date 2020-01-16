<?php

namespace MageSuite\OrderExport\Observer;

class ValidateOrderExport implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\OrderExport\Service\FTP\Validator
     */
    protected $validator;

    /**
     * @var \MageSuite\OrderExport\Api\ExportLogRepositoryInterface
     */
    protected $exportLogRepository;

    public function __construct(
        \MageSuite\OrderExport\Service\FTP\Validator $validator,
        \MageSuite\OrderExport\Api\ExportLogRepositoryInterface $exportLogRepository
    ) {
        $this->validator = $validator;
        $this->exportLogRepository = $exportLogRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $result = $observer->getEvent()->getData('result');
        $export = $observer->getEvent()->getData('export');

        $validationResult = $this->validator->validate($result);

        $validationResultText = $this->resultToText($validationResult);

        $export->setUploadedStatus($validationResultText);

        $this->exportLogRepository->save($export);
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
