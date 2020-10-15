<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use TickTackk\UpdateAnyResource\Globals;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;
use XF\Mvc\Entity\Entity;
use XF\Phrase;

class ResourceItem extends XFCP_ResourceItem
{
    /**
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canEdit(&$error = null)
    {
        /** @var ExtendedResourceVersionEntity|ExtendedResourceUpdateEntity $entity */
        $entity = Globals::$makeUseOfUpdateAnyPermission;

        if ($entity)
        {
            if (!$entity instanceof Entity)
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
     * @param Phrase|null $error
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
}