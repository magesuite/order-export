<?php
namespace MageSuite\OrderExport\Api\Data;

interface ExportLogSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \MageSuite\OrderExport\Api\Data\ExportLogInterface[]
     */
    public function getItems();

    /**
     * @param \MageSuite\OrderExport\Api\Data\ExportLogInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
