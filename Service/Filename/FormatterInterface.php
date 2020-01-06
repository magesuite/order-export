<?php
namespace MageSuite\OrderExport\Service\Filename;

interface FormatterInterface
{
    /**
     * @param string $incrementId
     * @param string $strategyType
     * @return mixed
     */
    public function getFilename($incrementId = '', $strategyType = '');
}
