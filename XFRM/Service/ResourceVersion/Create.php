<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceVersion;

use XF\App as BaseApp;
use XFRM\Entity\ResourceItem as ResourceItemEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;

/**
 * @method ExtendedResourceVersionEntity getVersion()
 */
class Create extends XFCP_Create
{
    public function __construct(BaseApp $app, ResourceItemEntity $resource)
    {
        parent::__construct($app, $resource);

        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            throw new \LogicException('Guests cannot add new version to resources.');
        }

        $version = $this->getVersion();
        $version->user_id = $visitor->user_id;
        $version->username = $visitor->username;
    }
}