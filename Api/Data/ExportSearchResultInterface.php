<?php
namespace MageSuite\OrderExport\Api\Data;

interface ExportSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \MageSuite\OrderExport\Api\Data\ExportInterface[]
     */
    public function getItems();

    /**
     * @param \MageSuite\OrderExport\Api\Data\ExportInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
