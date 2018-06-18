<?php

namespace TickTackk\UpdateAnyResource;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

/**
 * Class Setup
 *
 * @package TickTackk\UpdateAnyResource
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

    public function installStep1()
    {
        $sm = $this->schemaManager();

        foreach ($this->tables AS $tableName)
        {
            $sm->alterTable($tableName, function(Alter $table)
            {
                $table->addColumn('user_id', 'int');
                $table->addColumn('username', 'varchar', 100)->setDefault('');
            });
        }
    }

    public function uninstallStep1()
    {
        $sm = $this->schemaManager();

        foreach ($this->tables AS $tableName)
        {
            $sm->alterTable($tableName, function(Alter $table)
            {
                $table->dropColumns(['user_id', 'username']);
            });
        }
    }
}