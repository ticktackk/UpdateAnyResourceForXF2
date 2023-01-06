<?php

namespace TickTackk\UpdateAnyResource\XFRM\Notifier\ResourceUpdate;

use XF\Entity\User as UserEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;

/**
 * @property ExtendedResourceUpdateEntity $update
 *
 * @version 1.1.1
 */
class ResourceWatch extends XFCP_ResourceWatch
{
    /**
     * @return bool
     */
    public function canNotify(UserEntity $user)
    {
        return parent::canNotify($user)
            && !$user->isIgnoring($this->update->team_user_id);
    }

    /**
     * @return bool
     */
    public function sendAlert(UserEntity $user)
    {
        try
        {
            $update = $this->update;
            $resource = $update->Resource;

            $originalId = null;
            $originalName = null;

            if ($this->isUsingXFRM22ForTckUpdateAnyResource())
            {
                $originalId = $resource->user_id;
                $originalName = $resource->username;

                $resource->setAsSaved('user_id', $update->tck_uar_user_id);
                $resource->setAsSaved('username', $update->tck_uar_username);
            }

            return parent::sendAlert($user);
        }
        finally
        {
            if (!is_null($originalId) && !is_null($originalName))
            {
                $resource->setAsSaved('user_id', $originalId);
                $resource->setAsSaved('username', $originalName);
            }
        }
    }

    protected function isUsingXFRM22ForTckUpdateAnyResource() : bool
    {
        $addOns = $this->app()->container('addon.cache');
        if (!isset($addOns['XFRM']))
        {
            return false; // wut
        }

        return $addOns['XFRM'] >= 2020010;
    }
}