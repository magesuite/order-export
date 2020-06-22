<?php

namespace MageSuite\OrderExport\Console\Command;

class Export extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \MageSuite\OrderExport\Service\ExporterFactory
     */
    protected $exporterFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    public function __construct(
        \MageSuite\OrderExport\Service\ExporterFactory $exporterFactory,
        \Magento\Framework\App\State $state
    ) {
        parent::__construct();

        $this->exporterFactory = $exporterFactory;
        $this->state = $state;
    }

    protected function configure()
    {
        $this->addOption(
            'order_ids',
            '-i',
            \Symfony\Component\Console\Input\InputOption::VALUE_OPTIONAL,
            'Search orders by entity_id, separated by comma'
        );

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
            'status_after_export',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Status for exported orders'
        );

        $this->setName('orderexport:export:manual')
            ->setDescription('Manual run order export');
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $orderIds = empty($input->getOption('order_ids')) ? null : explode(',', $input->getOption('order_ids'));

        $data = [
            'type' => \MageSuite\OrderExport\Helper\Configuration::MANUAL_EXPORT_TYPE,
            'order_ids' => $orderIds,
            'status' => $input->getArgument('status'),
            'status_after_export' => $input->getArgument('status_after_export'),
            'date_from' => $input->getOption('date_from'),
            'date_to' => $input->getOption('date_to')
        ];

        $exporter = $this->exporterFactory->create(['data' => $data]);
        $exporter->execute();
    }
}
