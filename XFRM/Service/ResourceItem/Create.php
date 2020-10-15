<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceItem;

/**
 * Class Create
 *
 * @package TickTackk\UpdateAnyResource
 *
 * @property \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate $description
 * @property \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion $version
 */
class Create extends XFCP_Create
{
    protected function setupDefaults()
    {
        parent::setupDefaults();

        $this->description->user_id = $this->resource->user_id;
        $this->description->username = $this->resource->username;

        $this->version->user_id = $this->resource->user_id;
        $this->version->username = $this->resource->username;
    }
}