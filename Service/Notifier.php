<?php

declare(strict_types=1);

namespace MageSuite\OrderExport\Service;

class Notifier
{
    public const string COLLECTOR_NAME = 'Order Import/Export';

    public function __construct(
        protected \MageSuite\NotificationDashboard\Model\Command\Notification\AddNotification $addNotification,
        protected \MageSuite\NotificationDashboard\Api\CollectorRepositoryInterface $collectorRepository,
    ) {}

    public function notify(string $title, string $message): void
    {
        $collector = $this->collectorRepository->get(self::COLLECTOR_NAME);
        $this->addNotification->execute(__($message), $collector->getId(), $collector->getSeverity(), __($title));
    }
}
