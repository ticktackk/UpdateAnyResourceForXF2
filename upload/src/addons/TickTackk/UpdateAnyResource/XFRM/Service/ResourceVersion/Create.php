<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceVersion;

/**
 * Class Create
 *
 * @package TickTackk\UpdateAnyResource
 *
 * @property \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion $version
 */
class Create extends XFCP_Create
{
    /**
     * Create constructor.
     *
     * @param \XF\App                   $app
     * @param \XFRM\Entity\ResourceItem $resource
     */
    public function __construct(\XF\App $app, \XFRM\Entity\ResourceItem $resource)
    {
        parent::__construct($app, $resource);

        if (!$this->version->user_id)
        {
            $visitor = \XF::visitor();

            if (!$visitor->user_id)
            {
                throw new \LogicException('Guests cannot add new version to resources.');
            }

            $this->version->user_id = $visitor->user_id;
            $this->version->username = $visitor->username;
        }
    }
}