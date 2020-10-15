<?php

namespace TickTackk\UpdateAnyResource;

/**
 * Class Listener
 *
 * @package TickTackk\UpdateAnyResource
 */
class Listener
{
    /**
     * @param \XF\Service\User\ContentChange $changeService
     * @param array                          $updates
     */
    public static function userContentChangeInit(/** @noinspection PhpUnusedParameterInspection */
        \XF\Service\User\ContentChange $changeService, array &$updates)
    {
        $updates['xf_rm_resource_update'] = ['user_id', 'username'];
        $updates['xf_rm_resource_version'] = ['user_id', 'username'];
    }
}