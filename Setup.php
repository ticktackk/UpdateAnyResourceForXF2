<?php

namespace TickTackk\UpdateAnyResource;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;

/**
 * @version 1.1.1
 */
class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    protected $tables = [
        'xf_rm_resource_update',
        'xf_rm_resource_version'
    ];

    public function installStep1() : void
    {
        $sm = $this->schemaManager();

        foreach ($this->tables AS $tableName)
        {
            $sm->alterTable($tableName, function(Alter $table)
            {
                $table->addColumn('tck_uar_user_id', 'int');
                $table->addColumn('tck_uar_username', 'varchar', 100)->setDefault('');
            });
        }
    }

    public function upgrade1010170Step1() : void
    {
        $sm = $this->schemaManager();

        foreach ($this->tables AS $tableName)
        {
            $sm->alterTable($tableName, function(Alter $table)
            {
                foreach (['user_id', 'username'] AS $column)
                {
                    $table->renameColumn($column, "tck_uar_{$column}");
                }
            });
        }
    }

    public function uninstallStep1() : void
    {
        $sm = $this->schemaManager();

        foreach ($this->tables AS $tableName)
        {
            $sm->alterTable($tableName, function(Alter $table)
            {
                $table->dropColumns(['tck_uar_user_id', 'tck_uar_username']);
            });
        }
    }
}