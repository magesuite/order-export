<?php

namespace MageSuite\OrderExport\Block\Adminhtml\Export\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \MageSuite\OrderExport\Model\Config\Source\Order\Status
     */
    protected $statusSource;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \MageSuite\OrderExport\Model\Config\Source\Order\Status $statusSource,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->statusSource = $statusSource;
    }

    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('*/*/generatePost'),
                    'method' => 'post',
                ],
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Settings')]);
        $fieldset->addField(
            'order_status',
            'select',
            [
                'name' => 'order_status',
                'title' => __('Order status'),
                'label' => __('Order status'),
                'required' => true,
                'values' => $this->statusSource->toOptionArray()
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
