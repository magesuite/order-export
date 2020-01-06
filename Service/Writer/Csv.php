<?php

namespace MageSuite\OrderExport\Service\Writer;

class Csv implements \MageSuite\OrderExport\Service\Writer\WriterInterface
{
    /**
     * @var array
     */
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

    protected $fileHandler;

    /**
     * @param string $filePath
     */
    public function openFile($filePath)
    {
        $this->checkPath($filePath);
        $this->fileHandler = fopen($filePath, "w");
    }

    public function closeFile()
    {
        fclose($this->fileHandler);
    }

    public function write($data)
    {
        $line = [];
        foreach ($this->columns as $column) {
            if (array_key_exists($column, $data)) {
                $line[$column] = $data[$column];
            } else {
                $line[$column] = '';
            }
        }

        fputcsv($this->fileHandler, $line, ';', '"');
    }

    public function writeHeader()
    {
        fputcsv($this->fileHandler, $this->columns, ';', '"');
    }

    /**
     * @param string $filePath
     */
    private function checkPath($filePath)
    {
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}
