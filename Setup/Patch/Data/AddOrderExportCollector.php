<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Setup\Patch\Data;

class AddOrderExportCollector implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    public function __construct(
        protected \MageSuite\NotificationDashboard\Api\Data\CollectorInterfaceFactory $collectorFactory,
        protected \MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface $collectorRepository,
    ) {}

    public function apply(): self
    {
        $collector = $this->collectorFactory->create()
            ->setName(\MageSuite\OrderExport\Service\Notifier::COLLECTOR_NAME)
            ->setIsEnabled(1)
            ->setSeverity(\MageSuite\NotificationDashboard\Model\Source\Severity::SEVERITY_MAJOR)
            ->setLimitOnDashboard(10)
            ->setAddAdminNotification(0)
            ->setVisibleOnDashboard(1)
            ->setIsStatic(0);
        $this->collectorRepository->save($collector);

        return $this;
    }

    public function getAliases(): array
    {
        return [];
    }

    public static function getDependencies(): array
    {
        return [];
    }
}
