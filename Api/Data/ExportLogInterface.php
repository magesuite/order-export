<?php
namespace MageSuite\OrderExport\Api\Data;

interface ExportLogInterface
{
    /**
     * @param int $exportId
     * @return $this
     */
    public function setExportId($exportId);

    /**
     * @return int|null
     */
    public function getExportId();

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @param string $exportedFilename
     * @return $this
     */
    public function setExportedFilename($exportedFilename);

    /**
     * @return string|null
     */
    public function getExportedFilename();

    /**
     * @param int $exportedCount
     * @return $this
     */
    public function setExportedCount($exportedCount);

    /**
     * @return int|null
     */
    public function getExportedCount();

    /**
     * @param string $exportedIds
     * @return $this
     */
    public function setExportedIds($exportedIds);

    /**
     * @return string|null
     */
    public function getExportedIds();

    /**
     * @param string $startedAt
     * @return $this
     */
    public function setStartedAt($startedAt);

    /**
     * @return string|null
     */
    public function getStartedAt();

    /**
     * @param string $finishedAt
     * @return $this
     */
    public function setFinishedAt($finishedAt);

    /**
     * @return string|null
     */
    public function getFinishedAt();

    /**
     * @param string $usedOrderFilters
     * @return $this
     */
    public function setUsedOrderFilters($usedOrderFilters);

    /**
     * @return string|null
     */
    public function getUsedOrderFilters();

    /**
     * @param string $uploadedFilesStatus
     * @return $this
     */
    public function setUploadedFilesStatus($uploadedFilesStatus);

    /**
     * @return string|null
     */
    public function getUploadedFilesStatus();
}
