<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceItem;

use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;

/**
 * @method  ExtendedResourceUpdateEntity getDescription()
 * @method  ExtendedResourceVersionEntity getVersion()
 */
class Create extends XFCP_Create
{
    protected function setupDefaults()
    {
        parent::setupDefaults();

        $resource = $this->getResource();
        $description = $this->getDescription();
        $version = $this->getVersion();

        $description->user_id = $resource->user_id;
        $description->username = $resource->username;

        $version->user_id = $resource->user_id;
        $version->username = $resource->username;
    }
}