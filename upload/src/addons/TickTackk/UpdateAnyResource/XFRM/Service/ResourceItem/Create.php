<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceItem;

/**
 * Class Create
 *
 * @package TickTackk\UpdateAnyResource
 */
class Create extends XFCP_Create
{
    /**
     * @var \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate
     */
    protected $description;

    /**
     * @var \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion
     */
    protected $version;

    protected function setupDefaults()
    {
        parent::setupDefaults();

        $this->description->user_id = $this->resource->user_id;
        $this->description->username = $this->resource->username;

        $this->version->user_id = $this->resource->user_id;
        $this->version->username = $this->resource->username;
    }
}