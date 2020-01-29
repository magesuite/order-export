<?php

namespace MageSuite\OrderExport\Service\Writer;

class Xml extends \MageSuite\OrderExport\Service\Writer\Writer implements \MageSuite\OrderExport\Service\Writer\WriterInterface
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var array
     */
    protected $xmlNodeProcessors;

    public function __construct(
        \MageSuite\OrderExport\Helper\Configuration $configuration,
        array $xmlNodeProcessors
    ) {
        $this->configuration = $configuration;
        $this->xmlNodeProcessors = $xmlNodeProcessors;
    }

    public function write($orders)
    {
        $exportStrategy = $this->configuration->getExportStrategy();

        $xml = new \DOMDocument('1.0', 'utf-8');;

        if ($exportStrategy == \MageSuite\OrderExport\Model\Config\Source\Export\Strategy::EXPORT_STRATEGY_GROUPED) {
            $containerElement = $xml->createElement('Orders');
        } else {
            $containerElement = $xml;
        }

        foreach ($orders as $order) {
            $orderElement = $this->prepareOrderElement($xml, $order);
            $containerElement->appendChild($orderElement);
        }

        if ($exportStrategy == \MageSuite\OrderExport\Model\Config\Source\Export\Strategy::EXPORT_STRATEGY_GROUPED) {
            $xml->appendChild($containerElement);
        } else {
            $xml = $containerElement;
        }

        $xml->formatOutput = true;
        fputs($this->fileHandler, $xml->saveXML());
    }

    public function prepareOrderElement($xml, $order)
    {
        $orderElement = $xml->createElement('Order');

        /** @var \MageSuite\OrderExport\Service\Writer\XmlNodeProcessorInterface $nodeProcessor */
        foreach ($this->xmlNodeProcessors as $nodeProcessor) {
            $nodeXml = new \DOMDocument('1.0', 'utf-8');

            $node = $nodeProcessor->execute($nodeXml, $order);

            $nodeXml->appendChild($node);
            $nodeXml->formatOutput = true;

            $orderElement->appendChild($xml->importNode($nodeXml->documentElement->cloneNode(true), true));
        }

        return $orderElement;
    }
}
