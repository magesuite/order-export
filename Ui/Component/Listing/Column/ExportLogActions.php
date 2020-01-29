<?php
namespace MageSuite\OrderExport\Ui\Component\Listing\Column;

class ExportLogActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[$item['id_field_name']])) {
                    $showUrlPath = $this->getData('config/showUrlPath') ?: '#';
                    $item[$this->getData('name')] = [
                        'show' => [
                            'href' => $this->urlBuilder->getUrl($showUrlPath, [
                                $item['id_field_name'] => $item[$item['id_field_name']],
                            ]),
                            'label' => __('Show')
                        ]
                    ];
                    unset($item);
                }
            }
        }
        return $dataSource;
    }
}
