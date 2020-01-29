<?php
namespace MageSuite\OrderExport\Model\Config;

class StatusesList
{
    /**
     * @var string[]
     */
    protected $statuses;

    /**
     * Constructor
     *
     * @param array $statuses
     */
    public function __construct(array $statuses = [])
    {
        $this->statuses = $statuses;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatuses()
    {
        return $this->statuses;
    }
}
