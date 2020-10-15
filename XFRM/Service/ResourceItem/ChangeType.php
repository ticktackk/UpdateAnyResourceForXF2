<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceItem;

use XF\App as BaseApp;
use XFRM\Entity\ResourceItem;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;

/**
 * @property ExtendedResourceVersionEntity $version
 */
class ChangeType extends XFCP_ChangeType
{
    public function __construct(BaseApp $app, ResourceItem $resource)
    {
        parent::__construct($app, $resource);

        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            throw new \LogicException('Guests cannot add new update to resources.');
        }

        $this->version->user_id = $visitor->user_id;
        $this->version->username = $visitor->username;
    }
}