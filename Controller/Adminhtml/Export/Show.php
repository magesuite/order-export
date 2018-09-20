<?php

namespace MageSuite\OrderExport\Controller\Adminhtml\Export;

class Show extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \MageSuite\OrderExport\Repository\ExportRepository
     */
    private $exportRepository;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \MageSuite\OrderExport\Repository\ExportRepository $exportRepository,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
    ) {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->exportRepository = $exportRepository;
        $this->redirectFactory = $redirectFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $exportId = $this->getRequest()->getParam('id', 0);

        $export = $this->exportRepository->getById($exportId);

        $this->registry->register('orderexport_export', $export);

        $resultPage->getConfig()->getTitle()->prepend(__('Orders Export Log'));

        if(empty($export)) {
            $redirect = $this->redirectFactory->create();
            $redirect->setPath('*/*/grid');
            return $redirect;
        }

        return $resultPage;
    }
}
