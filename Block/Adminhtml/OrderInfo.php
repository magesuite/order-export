<?php
namespace MageSuite\OrderExport\Block\Adminhtml;

class OrderInfo extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface
     */
    protected $exportStatusRepository;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \MageSuite\OrderExport\Model\Collections\Export
     */
    protected $exportLogCollection;

    /**
     * @var \MageSuite\OrderExport\Model\Config\StatusesList
     */
    protected $statusesList;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\UrlInterface $urlBuilder,
        \MageSuite\OrderExport\Api\ExportStatusRepositoryInterface $exportStatusRepository,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \MageSuite\OrderExport\Model\Collections\Export $exportLogCollection,
        \MageSuite\OrderExport\Model\Config\StatusesList $statusesList
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->urlBuilder = $urlBuilder;
        $this->exportStatusRepository = $exportStatusRepository;
        $this->directoryList = $directoryList;
        $this->exportLogCollection = $exportLogCollection;
        $this->statusesList = $statusesList;
    }

    public function getOrder()
    {
        $order = $this->registry->registry('sales_order');
        if (!is_object($order)) {
            return false;
        }

        return $order;
    }

    public function canShow()
    {
        if (empty($this->getStatuses())) {
            return false;
        }
        return true;
    }

    public function getStatuses()
    {
        $order = $this->getOrder();

        if (!$order) {
            return [];
        }

        $erpStatus = $this->exportStatusRepository->getOrderData($order->getId());

        if ($erpStatus && $erpStatus->getStatusData()) {
            return json_decode($erpStatus->getStatusData(), true);
        }

        return [];
    }

    public function getMatchedStatuses()
    {
        $completedStatuses = $this->getStatuses();
        $definedStatuses = $this->statusesList->getStatuses();

        foreach ($definedStatuses as $code => $status) {
            if (!isset($completedStatuses[$code])) {
                continue;
            }
            $definedStatuses[$code]['completed'] = $completedStatuses[$code]['completed'];
            $definedStatuses[$code]['error'] = $completedStatuses[$code]['error'];
            if (isset($completedStatuses[$code]['error_message'])) {
                $definedStatuses[$code]['error_message'] = $completedStatuses[$code]['error_message'];
            }
        }

        usort($definedStatuses, function ($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        foreach ($definedStatuses as $i => $status) {
            if ($status['disabled']) {
                unset($definedStatuses[$i]);
            }
        }
        return $definedStatuses;
    }

    public function getStatusImage($type)
    {
        $imagePath = __DIR__ . '/../../view/adminhtml/web/images/'. $type .'.svg';

        $fileContent = file_get_contents($imagePath);

        return $fileContent;
    }

    public function getStatusCssClass($completed = false, $error = false)
    {
        if ($error) {
            return 'error';
        }

        if ($completed) {
            return 'completed';
        }

        return '';
    }

    public function getDownloadUrl()
    {
        /**
         * This confusing condition is made because GDPR module will return false if admin can see customer data
         */
        if ($this->isAllowed()) {
            return false;
        }

        $statuses = $this->getStatuses();
        $fileName = (isset($statuses['file_generated']) && isset($statuses['file_generated']['file_name'])) ? $statuses['file_generated']['file_name'] : false;

        if (!$fileName) {
            return false;
        }
        $path = $this->directoryList->getPath('var') . '/orderexport/' . $fileName;

        if (!file_exists($path)) {
            return false;
        }

        return $this->urlBuilder->getUrl('orderexport/index/preview', ['file_name' => $fileName]);
    }

    public function getExportLogUrl()
    {
        if (!$order = $this->getOrder()) {
            return false;
        }

        $incrementId = $order->getIncrementId();

        $logsCollection = $this->exportLogCollection;

        $logsCollection->addFieldToFilter('success_ids', ['like' => '%' . $incrementId . '%']);

        if (!$logsCollection->getSize()) {
            return false;
        }

        $log = $logsCollection->getFirstItem();

        return $this->urlBuilder->getUrl('orderexport/export/show', ['id' => $log->getExportId()]);
    }

    public function isAllowed()
    {
        return $this->_authorization->isAllowed('MageSuite_Gdpr::hide_customer_data');
    }
}
