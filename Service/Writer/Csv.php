<?php

namespace MageSuite\OrderExport\Service\Writer;

class Csv extends \MageSuite\OrderExport\Service\Writer\Writer implements \MageSuite\OrderExport\Service\Writer\WriterInterface
{
    protected $delimiter = ';';
    protected $enclosure = '"';

    protected $columns = [
        'increment_id',
        'name',
        'telephone',
        'email',
        'company',
        'address',
        'city',
        'postcode',
        'region',
        'country',
        'shipping_name',
        'shipping_company',
        'shipping_address',
        'shipping_city',
        'shipping_postcode',
        'shipping_region',
        'shipping_country',
        'shipping_amount',
        'shipping_method',
        'payment_method',
        'sku',
        'product_name',
        'quantity',
        'price',
    ];

    public function writeHeader()
    {
        fputcsv($this->fileHandler, $this->getColumns(), $this->getDelimiter(), $this->getEnclosure());
    }

    public function write($order)
    {
        $line = [];
        foreach ($this->getColumns() as $column) {
            $line[$column] = $order[$column] ?? '';
        }

        fputcsv($this->fileHandler, $line, $this->getDelimiter(), $this->getEnclosure());
    }

    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function getDelimiter()
    {
        return $this->delimiter;
    }

    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    public function getEnclosure()
    {
        return $this->enclosure;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
