<?php

namespace MageSuite\OrderExport\Model;

interface OrderFilterInterface
{
    /**
     * @param array $filterData
     * @return array
     */
    public function getFilters($filterData);
}
