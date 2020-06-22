<?php

namespace MageSuite\OrderExport\Model;

class OrderFilterSelected implements \MageSuite\OrderExport\Model\OrderFilterInterface
{
    const USED_FILTER_TEXT_FORMAT = 'Field %s, value %s, condition %s';

    public function getFilters($filterData = [])
    {
        $filters = [];

        $orderIds = $filterData['order_ids'] ?? null;

        if ($orderIds) {
            $filters[] = ['field' => 'entity_id', 'value' => $orderIds, 'condition' => 'in'];
        }

        return $filters;
    }

    public function getUsedOrderFilters($filters = [])
    {
        if (empty($filters)) {
            return null;
        }

        $usedFilters = [];
        foreach ($filters as $filter) {
            $filterValue = is_array($filter['value']) ? implode(',', $filter['value']) : $filter['value'];

            $usedFilters[] = sprintf(self::USED_FILTER_TEXT_FORMAT, $filter['field'], $filterValue, $filter['condition']);
        }

        return implode("\n", $usedFilters);
    }
}
