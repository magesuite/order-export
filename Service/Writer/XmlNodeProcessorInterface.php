<?php

namespace MageSuite\OrderExport\Service\Writer;

interface XmlNodeProcessorInterface
{
    /**
     * @param \DOMDocument $xml
     * @param array$order
     * @return \DOMDocument $order
     */
    public function execute($xml, $order);
}
