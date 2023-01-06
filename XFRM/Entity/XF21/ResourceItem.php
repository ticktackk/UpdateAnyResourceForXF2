<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity\XF21;

use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceItemTrait;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\XFCP_ResourceItem;
use XFRM\Entity\ResourceUpdate as ResourceUpdateEntity;
use XFRM\Entity\ResourceVersion as ResourceVersionEntity;

class ResourceItem extends XFCP_ResourceItem
{
    use ResourceItemTrait;

    /**
     * @return ResourceUpdateEntity|ExtendedResourceUpdateEntity
     *
     * @noinspection PhpSignatureMismatchDuringInheritanceInspection
     */
    public function getNewUpdate()
    {
        return $this->setupNewUpdateForTckUpdateAnyResource(
            parent::getNewUpdate()
        );
    }

    /**
     * @return ResourceVersionEntity|ExtendedResourceVersionEntity
     *
     * @noinspection PhpSignatureMismatchDuringInheritanceInspection
     */
    public function getNewVersion()
    {
        return $this->setupNewVersionForTckUpdateAnyResource(
            parent::getNewVersion()
        );
    }
}