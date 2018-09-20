<?php

namespace MageSuite\OrderExport\Setup;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function upgrade(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            if (!$installer->tableExists('orderexport_log')) {
                $table = $installer->getConnection()->newTable($installer->getTable('orderexport_log'));

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
                )
                    ->addColumn(
                        'type',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        20,
                        [],
                        'Export type'
                    )
                    ->addColumn(
                        'exported_filename',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [
                            'nullable' => false,
                            'default' => ''
                        ],
                        'Exported filename'
                    )
                    ->addColumn(
                        'success',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [],
                        'Exported success count'
                    )
                    ->addColumn(
                        'success_ids',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'Exported successfully'
                    )
                    ->addColumn(
                        'started_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Export started at'
                    )
                    ->addColumn(
                        'finished_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        [],
                        'Export finished at'
                    )
                    ->setComment('Order Export log table');

                $installer->getConnection()->createTable($table);
            }
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $tableName = $setup->getTable('orderexport_log');

            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $installer->getConnection();

                if(!$connection->tableColumnExists($setup->getTable($tableName), 'search_order_status')) {
                    $connection->addColumn($tableName, 'search_order_status', [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => false,
                            'size' => 255,
                            'comment' => 'Search order status',
                            'default' => ''
                        ]
                    );
                }
            }
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $tableName = $setup->getTable('orderexport_log');

            if ($installer->getConnection()->isTableExists($tableName) == true) {
                $connection = $installer->getConnection();

                if(!$connection->tableColumnExists($setup->getTable($tableName), 'uploaded_files_status')) {
                    $connection->addColumn($tableName, 'uploaded_files_status', [
                            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                            'nullable' => true,
                            'size' => 255,
                            'comment' => 'Uploaded Files Status',
                            'default' => ''
                        ]
                    );
                }
            }
        }

        $installer->endSetup();
    }


}