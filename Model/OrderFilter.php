<?php

namespace MageSuite\OrderExport\Model;

class OrderFilter implements \MageSuite\OrderExport\Model\OrderFilterInterface
{
    const DAY_START_EXPRESSION = 'Y-m-d 00:00:00';
    const DAY_END_EXPRESSION = 'Y-m-d 23:59:59';

    const USED_FILTER_TEXT_FORMAT = 'Field %s, value %s, condition %s';

    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\OrderExport\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getFilters($filterData = [])
    {
        $filters = [];

        $status = $filterData['status'] ?? null;
        $dateFrom = $filterData['date_from'] ?? null;
        $dateTo = $filterData['date_to'] ?? null;

        if ($status) {
            $filters[] = ['field' => 'status', 'value' => $status, 'condition' => 'eq'];
        }

        $now = new \DateTime();
        $shouldExportOrdersDaily = $this->configuration->shouldExportOrdersDaily();

        if ($dateFrom) {
            $dateFrom = new \DateTime($dateFrom);
            $from = $dateFrom->format(self::DAY_START_EXPRESSION);
        } elseif ($shouldExportOrdersDaily) {
            $from = $now->format(self::DAY_START_EXPRESSION);
        } else {
            $from = null;
        }

        if ($from) {
            $filters[] = ['field' => 'created_at', 'value' => $from, 'condition' => 'gteq'];
        }

        if ($dateTo) {
            $dateTo = new \DateTime($dateTo);
            $to = $dateTo->format(self::DAY_END_EXPRESSION);
        } elseif ($shouldExportOrdersDaily) {
            $to = $now->format(self::DAY_END_EXPRESSION);
        } else {
            $to = null;
        }

        if ($to) {
            $filters[] = ['field' => 'created_at', 'value' => $to, 'condition' => 'lteq'];
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
