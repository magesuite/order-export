<?php

namespace MageSuite\OrderExport\Model;

interface OrderFilterInterface
{
    /**
     * @param array|null $filterData
     * @return array
     */
    public function getFilters($filterData = []);
}
