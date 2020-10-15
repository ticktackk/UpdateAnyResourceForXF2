<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceItem;

use XF\App as BaseApp;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Repository;
use XF\Service\AbstractService;
use XF\Mvc\Entity\Manager as EntityManager;
use XF\Job\Manager as JobManager;
use XFRM\Entity\ResourceItem;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ExtendedResourceVersionEntity;

/**
 * @property ExtendedResourceVersionEntity $version
 */
class ChangeType extends XFCP_ChangeType
{
    public function __construct(\XF\App $app, ResourceItem $resource)
    {
        parent::__construct($app, $resource);

        if (!$this->version->user_id)
        {
            $resource = $this->getResource();
            
            $this->version->user_id = $resource->user_id;
            $this->version->username = $resource->username;
        }
    }
}