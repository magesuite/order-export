<?php
namespace MageSuite\OrderExport\Service;

interface UploadValidatorInterface
{
    /**
     * @param array $exportResult
     * @return array
     */
    public function validate($exportResult);
}
