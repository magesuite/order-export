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
     * @param int $success
     * @return $this
     */
    public function setSuccess($success);

    /**
     * @return int|null
     */
    public function getSuccess();

    /**
     * @param string $successIds
     * @return $this
     */
    public function setSuccessIds($successIds);

    /**
     * @return string|null
     */
    public function getSuccessIds();

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
     * @param string $searchOrderStatus
     * @return $this
     */
    public function setSearchOrderStatus($searchOrderStatus);

    /**
     * @return string|null
     */
    public function getSearchOrderStatus();

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
