<?php

namespace TickTackk\UpdateAnyResource;

use XF\Service\User\ContentChange as UserContentChangeSvc;

class Listener
{
    /**
     * @param UserContentChangeSvc $changeService
     * @param array                updates
     */
    public static function userContentChangeInit(UserContentChangeSvc $changeService, array &$updates)
    {
        $updates['xf_rm_resource_update'] = ['user_id', 'username'];
        $updates['xf_rm_resource_version'] = ['user_id', 'username'];
    }
}