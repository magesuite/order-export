<?php

namespace MageSuite\OrderExport\Model;

class OrderFilter implements \MageSuite\OrderExport\Model\OrderFilterInterface
{
    /**
     * @var \MageSuite\OrderExport\Helper\Configuration
     */
    protected $configuration;

    public function __construct(\MageSuite\OrderExport\Helper\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getFilters($filterData)
    {
        $filters = [];

        $status = $filterData['status'] ?? null;
        $dataFrom = $filterData['date_from'] ?? null;
        $dateTo = $filterData['date_to'] ?? null;

        if ($status) {
            $filters[] = ['field' => 'status', 'value' => $status, 'condition' => 'eq'];
        }

        $now = new \DateTime();
        $shouldExportOrdersDaily = $this->configuration->shouldExportOrdersDaily();

        if ($dataFrom) {
            $dateFrom = new \DateTime($dataFrom);
            $from = $dateFrom->format('Y-m-d 00:00:00');
        } elseif ($shouldExportOrdersDaily) {
            $from = $now->format('Y-m-d 00:00:00');
        } else {
            $from = null;
        }

        if ($from) {
            $filters[] = ['field' => 'created_at', 'value' => $from, 'condition' => 'gteq'];
        }

        if ($dateTo) {
            $dateTo = new \DateTime($dateTo);
            $to = $dateTo->format('Y-m-d 23:59:59');
        } elseif ($shouldExportOrdersDaily) {
            $to = $now->format('Y-m-d 23:59:59');
        } else {
            $to = null;
        }

        if ($to) {
            $filters[] = ['field' => 'created_at', 'value' => $to, 'condition' => 'lteq'];
        }

        return $filters;
    }
}
