<?php
namespace MageSuite\OrderExport\Ui\Component\Listing\Column;

class ErpStatus extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $styles = [
        'ready_to_generate' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #fffbbe;
                    border: 1px solid #f29d66;
                    color: #e9571e;',
        'file_generated' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #fffbbe;
                    border: 1px solid #f29d66;
                    color: #e9571e;',
        'file_exported' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'file_processed_by_erp' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'payment_created' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'order_shipped' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'order_completed' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #d0e5a9;
                    border: 1px solid #5b8116;
                    color: #185b00;',
        'error' => 'display:block;width:100%;text-align:center;font-weight: bold; padding: 5px; 
                    background-color: #f9d4d4;
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
        $orderErpStatus = $this->exportStatusRepository->getOrderData($orderId);

        if (!$orderErpStatus) {
            return '';
        }

        if (!$encodedStatus = json_decode($orderErpStatus->getStatusData(), true)) {
            return '';
        }

        $rendererData = $this->getRendererData(end($encodedStatus));

        if (!$rendererData) {
            return '';
        }

        return sprintf('<span style="%s">%s</span>', $rendererData['style'], $rendererData['label']);
    }

    protected function getRendererData($orderErpStatus)
    {
        $statusData = $this->getStatusData($orderErpStatus['status']);

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
        return $styles[$status];
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
