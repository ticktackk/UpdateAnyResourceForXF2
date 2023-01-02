<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity\XF22;

use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceItemTrait;
use TickTackk\UpdateAnyResource\XFRM\Entity\XFCP_ResourceItem;
use XF\Entity\User;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;
use XFRM\Entity\ResourceUpdate as ResourceUpdateEntity;
use XFRM\Entity\ResourceVersion as ResourceVersionEntity;

class ResourceItem extends XFCP_ResourceItem
{
    use ResourceItemTrait;

    /**
     * @param User|null $teamUser
     *
     * @return ResourceUpdateEntity|ExtendedResourceUpdateEntity
     */
    public function getNewUpdate(User $teamUser = null)
    {
        return $this->setupNewUpdateForTckUpdateAnyResource(
            parent::getNewUpdate($teamUser),
            $teamUser
        );
    }

    /**
     * @param User|null $teamUser
     *
     * @return ResourceVersionEntity|ExtendedResourceVersionEntity
     */
    public function getNewVersion(User $teamUser = null)
    {
        return $this->setupNewVersionForTckUpdateAnyResource(
            parent::getNewVersion($teamUser),
            $teamUser
        );
    }
}