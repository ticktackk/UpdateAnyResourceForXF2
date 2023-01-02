<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceItem;

use XF\Entity\User as UserEntity;

/**
 * Class Reassign
 *
 * @package TickTackk\UpdateAnyResource
 */
class Reassign extends XFCP_Reassign
{
    /**
     * @return bool
     */
    public function reassignTo(UserEntity $newUser)
    {
        $resource = $this->getResource();
        $oldUserId = $resource->user_id;
        $resourceId = $resource->resource_id;

        $reassigned = parent::reassignTo($newUser);

        if ($reassigned)
        {
            $db = $this->db();
            foreach (['xf_rm_resource_update', 'xf_rm_resource_version'] AS $table)
            {
                $db->update($table, [
                    'tck_uar_user_id' => $newUser->user_id,
                    'tck_uar_username' => $newUser->username
                ], 'tck_uar_user_id = ? AND resource_id = ?', [$oldUserId, $resourceId]);
            }
        }

        return $reassigned;
    }
}