<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use TickTackk\UpdateAnyResource\Globals;

/**
 * Class ResourceItem
 *
 * @package TickTackk\UpdateAnyResource
 */
class ResourceItem extends XFCP_ResourceItem
{
    /**
     * @param null $error
     *
     * @return bool
     */
    public function canEdit(&$error = null)
    {
        /** @var \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion|\TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate $entity */
        $entity = Globals::$makeUseOfUpdateAnyPermission;

        if ($entity)
        {
            if (!$entity instanceof \XF\Mvc\Entity\Entity)
            {
                throw new \LogicException('Expected entity to be instance of Entity.');
            }

            if (!$entity->user_id_)
            {
                return parent::canEdit($error);
            }

            $visitor = \XF::visitor();
            if (!$visitor->user_id)
            {
                return false;
            }

            if ($this->hasPermission('editAny'))
            {
                return true;
            }

            return (
                $entity->user_id === $visitor->user_id
                && $this->hasPermission('updateOwn')
            );
        }

        return parent::canEdit($error);
    }

    /**
     * @param null $error
     *
     * @return bool
     */
    public function canReleaseUpdate(&$error = null)
    {
        $visitor = \XF::visitor();

        if (!$visitor->user_id || !$this->user_id)
        {
            return parent::canReleaseUpdate($error);
        }

        if ($visitor->user_id !== $this->user_id)
        {
            if ($this->isVisible())
            {
                return $this->hasPermission('updateAny');
            }

            return false;
        }

        return parent::canReleaseUpdate($error);
    }

    /**
     * @return \XF\Mvc\Entity\Entity
     */
    public function getNewUpdate()
    {
        $update = parent::getNewUpdate();

        if ($this->exists())
        {
            $update->user_id = $this->user_id;
            $update->username = $this->username;
        }

        return $update;
    }

    /**
     * @return \XF\Mvc\Entity\Entity
     */
    public function getNewVersion()
    {
        $version = parent::getNewVersion();

        if ($this->exists())
        {
            $version->user_id = $this->user_id;
            $version->username = $this->username;
        }

        return $version;
    }
}