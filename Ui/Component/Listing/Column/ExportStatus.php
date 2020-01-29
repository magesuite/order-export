<?php
namespace MageSuite\OrderExport\Ui\Component\Listing\Column;

class ExportStatus extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $globalStyle = 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px;';

    protected $styles = [
        'ready_to_generate' => 'background-color: #fffbbe;
                    border: 1px solid #f29d66;
                    color: #e9571e;',
        'file_generated' => 'background-color: #fffbbe;
                    border: 1px solid #f29d66;
                    color: #e9571e;',
        'file_exported' => 'background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'file_processed_by_erp' => 'background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'payment_created' => 'background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'order_shipped' => 'background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'order_completed' => 'background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'error' => 'background-color: #f9d4d4;
                    border: 1px solid #e22626;
                    color: #e22626;'
    ];

    /**
     * @var \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface
     */
    protected $exportStatusRepository;

    /**
     * @var \MageSuite\OrderExport\Model\Config\StatusesList
     */
    protected $statusesList;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface $exportStatusRepository,
        \MageSuite\OrderExport\Model\Config\StatusesList $statusesList,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->exportStatusRepository = $exportStatusRepository;
        $this->statusesList = $statusesList;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $orderId  = $item['entity_id'];
                $html = $this->getHtml($orderId);
                $item[$fieldName] = $html;
            }
        }

        return $dataSource;
    }

    public function getHtml($orderId)
    {
        $orderExportStatus = $this->exportStatusRepository->getById($orderId);

        if (!$orderExportStatus) {
            return '';
        }

        if (!$encodedStatus = json_decode($orderExportStatus->getStatusData(), true)) {
            return '';
        }

        $rendererData = $this->getRendererData(end($encodedStatus));

        if (!$rendererData) {
            return '';
        }

        return sprintf('<span style="%s">%s</span>', $rendererData['style'], $rendererData['label']);
    }

    protected function getRendererData($exportStatus)
    {
        $statusData = $this->getStatusData($exportStatus['status']);

        if (!$statusData) {
            return false;
        }

        return [
            'label' => $statusData['label'],
            'style' => $this->getStyle($statusData['status'])
        ];
    }

    public function getStyle($status)
    {
        $styles = $this->styles;

        return $this->globalStyle . $styles[$status];
    }

    public function getStatusData($status)
    {
        $statusesList = $this->statusesList->getStatuses();

        if (!isset($statusesList[$status])) {
            return false;
        }

        return $statusesList[$status];
    }
}
