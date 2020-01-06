<?php

namespace MageSuite\OrderExport\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->createOrderExportLogTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $this->addSearchOrderStatusColumnToLogTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $this->addUploadedFilesStatusColumnToLogTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $this->addAdditionalTableForErpOrderStatus($setup);
        }

        $setup->endSetup();
    }

    protected function createOrderExportLogTable($setup)
    {
        if ($setup->getConnection()->isTableExists('orderexport_log')) {
            return;
        }

        $table = $setup->getConnection()->newTable($setup->getTable('orderexport_log'));

        $table->addColumn(
            'export_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true,
                'unsigned' => true,
            ],
            'Export ID'
        )->addColumn(
            'type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            20,
            [],
            'Export type'
        )->addColumn(
            'exported_filename',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [
                'nullable' => false,
                'default' => ''
            ],
            'Exported filename'
        )->addColumn(
            'success',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Exported success count'
        )->addColumn(
            'success_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Exported successfully'
        )->addColumn(
            'started_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'Export started at'
        )->addColumn(
            'finished_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'Export finished at'
        )->setComment('Order Export log table');

        $setup->getConnection()->createTable($table);
    }

    protected function addSearchOrderStatusColumnToLogTable($setup)
    {
        $tableName = $setup->getTable('orderexport_log');

        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $connection = $setup->getConnection();

            if (!$connection->tableColumnExists($setup->getTable($tableName), 'search_order_status')) {
                $connection->addColumn($tableName, 'search_order_status', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'size' => 255,
                    'comment' => 'Search order status',
                    'default' => ''
                ]);
            }
        }
    }

    protected function addUploadedFilesStatusColumnToLogTable($setup)
    {
        $tableName = $setup->getTable('orderexport_log');

        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $connection = $setup->getConnection();

            if (!$connection->tableColumnExists($setup->getTable($tableName), 'uploaded_files_status')) {
                $connection->addColumn($tableName, 'uploaded_files_status', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'size' => 255,
                    'comment' => 'Uploaded Files Status',
                    'default' => ''
                ]);
            }
        }
    }

    public function addAdditionalTableForErpOrderStatus($setup)
    {
        if (!$setup->tableExists('order_export_status')) {
            $table = $setup->getConnection()->newTable($setup->getTable('order_export_status'));

            $table->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'ID'
            )
                ->addColumn(
                    'order_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'Order ID'
                )
                ->addColumn(
                    'increment_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    [],
                    'increment_id'
                )
                ->addColumn(
                    'status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'status'
                )
                ->setComment('Erp order export status');

            $setup->getConnection()->createTable($table);
        }
    }
}
