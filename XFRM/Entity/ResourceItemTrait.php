<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use XF\Entity\User as UserEntity;
use XF\Mvc\Entity\Entity;
use XF\Phrase;
use XFRM\Entity\ResourceUpdate as ResourceUpdateEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;
use XFRM\Entity\ResourceVersion as ResourceVersionEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;

/**
 * COLUMNS
 * @property int $tck_uar_user_id
 *
 * METHODS
 * @method mixed setAsSaved(string $key, mixed $value)
 *
 * @since 1.1.1
 */
trait ResourceItemTrait
{
    /**
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canReleaseUpdate(&$error = null)
    {
        $visitor = \XF::visitor();
        $canReleaseUpdate = parent::canReleaseUpdate($error);

        $isTeamMember = false;
        if (method_exists($this, 'isTeamMember'))
        {
            $isTeamMember = $this->isTeamMember($visitor->user_id);
        }

        if (!$canReleaseUpdate
            && $visitor->user_id
            && ($visitor->user_id !== $this->user_id)
            && !$isTeamMember
            && $this->hasPermission('updateAny')
        )
        {
            $canReleaseUpdate = true;
        }

        return $canReleaseUpdate;
    }

    public function runActionForTckUpdateAnyResource(int $userId, callable $action)
    {
        $originalUserId = $this->user_id;

        try
        {
            $isTeamMember = false;
            if (method_exists($this, 'isTeamMember'))
            {
                $isTeamMember = $this->isTeamMember($userId);
            }

            if (!$isTeamMember && ($originalUserId !== $userId))
            {
                $this->setAsSaved('user_id', $userId);
            }

            return $action();
        }
        finally
        {
            if (!$isTeamMember && ($userId !== $originalUserId))
            {
                $this->setAsSaved('user_id', $userId);
            }
        }
    }


    /**
     * @param ResourceUpdateEntity|ExtendedResourceUpdateEntity|Entity $update
     * @param UserEntity|null $teamUser
     *
     * @return ResourceUpdateEntity|ExtendedResourceUpdateEntity
     */
    protected function setupNewUpdateForTckUpdateAnyResource(
        ResourceUpdateEntity $update,
        UserEntity $teamUser = null
    ) : ResourceUpdateEntity
    {
        if (!$teamUser)
        {
            $teamUser = \XF::visitor();
        }

        $update->tck_uar_user_id = $teamUser->user_id;
        $update->tck_uar_username = $teamUser->username;

        return $update;
    }

    /**
     * @param ResourceVersionEntity|ExtendedResourceVersionEntity|Entity $version
     * @param UserEntity|null $teamUser
     *
     * @return ResourceVersionEntity|ExtendedResourceVersionEntity
     */
    protected function setupNewVersionForTckUpdateAnyResource(
        ResourceVersionEntity $version,
        UserEntity $teamUser = null
    ) : ResourceVersionEntity
    {
        if (!$teamUser)
        {
            $teamUser = \XF::visitor();
        }

        $version->tck_uar_user_id = $teamUser->user_id;
        $version->tck_uar_username = $teamUser->username;

        return $version;
    }
}