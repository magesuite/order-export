<?php

namespace MageSuite\OrderExport\Console\Command;

class Export extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\OrderExport\Service\ExportFactory
     */
    protected $exportFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    public function __construct(
        \MageSuite\OrderExport\Service\ExportFactory $exporterFactory,
        \Magento\Framework\App\State $state
    ) {
        parent::__construct();

        $this->exportFactory = $exporterFactory;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->addOption(
            'date_from',
            '-f',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Type Date from which order will be exported (format Y-m-d), if empty orders from current date will be taken'
        );

        $this->addOption(
            'date_to',
            '-t',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Type Date to which order will be exported (format Y-m-d), if empty orders from current date will be taken'
        );

        $this->addArgument(
            'status',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Search Status'
        );

        $this->addArgument(
            'new_status',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'New status for exported orders'
        );

        $this->setName('orderexport:export:manual')
            ->setDescription('Manual run order export');
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $data = [
            'type' => 'manual',
            'status' => $input->getArgument('status'),
            'date_from' => $input->getOption('date_from'),
            'date_to' => $input->getOption('date_to')
        ];

        /** @var \MageSuite\OrderExport\Service\Export $export */
        $export = $this->exportFactory->create(['data' => $data]);

        $result = $export->execute();

        $newStatus = $input->getArgument('new_status');

        if (!empty($newStatus)) {
            foreach ($result['ordersData'] as $order) {
                $order->addStatusToHistory($newStatus, 'Order has been exported manually');
                $order->save();
            }
        }
    }
}
