<?php

namespace TickTackk\UpdateAnyResource;

use XF\Service\User\ContentChange as UserContentChangeSvc;

/**
 * @version 1.1.1
 */
class Listener
{
    /**
     * @param UserContentChangeSvc $changeService
     * @param array $updates
     *
     * @return void
     */
    public static function userContentChangeInit(UserContentChangeSvc $changeService, array &$updates) : void
    {
        $updates['xf_rm_resource_update'] = ['tck_uar_user_id', 'tck_uar_username'];
        $updates['xf_rm_resource_version'] = ['tck_uar_user_id', 'tck_uar_username'];
    }
}