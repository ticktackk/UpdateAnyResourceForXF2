<?php

namespace TickTackk\UpdateAnyResource;

use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ResourceUpdateEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceVersion as ResourceVersionEntity;

class Globals
{
    /**
     * @var null|ResourceVersionEntity|ResourceUpdateEntity
     */
    public static $makeUseOfUpdateAnyPermission = null;

    private function __construct()
    {
    }
}