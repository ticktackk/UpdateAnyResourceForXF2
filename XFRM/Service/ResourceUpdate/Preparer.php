<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceUpdate;

use XF\Entity\User as UserEntity;

/**
 * @since 1.1.1
 */
class Preparer extends XFCP_Preparer
{
    /**
     * @var null|bool
     */
    protected $nullifyUserForTckUpdateAnyResource = null;

    /**
     * @var null|UserEntity
     */
    protected $originalUserForTckUpdateAnyResource = null;

    protected function isUsingXFRM22ForTckUpdateAnyResource() : bool
    {
        $addOns = $this->app->container('addon.cache');
        if (!isset($addOns['XFRM']))
        {
            return false; // wut
        }

        return $addOns['XFRM'] >= 2020010;
    }

    public function setNullifyUserForTckUpdateAnyResource(bool $nullifyUser) : void
    {
        if (!$this->isUsingXFRM22ForTckUpdateAnyResource())
        {
            return;
        }

        $update = $this->getUpdate();
        $resource = $update->Resource;

        if ($nullifyUser && !$this->getOriginalUserForTckUpdateAnyResource())
        {
            $this->originalUserForTckUpdateAnyResource = $resource->User;
        }

        $this->nullifyUserForTckUpdateAnyResource = $nullifyUser;
    }

    public function getNullifyUserForTckUpdateAnyResource() :? bool
    {
        return $this->nullifyUserForTckUpdateAnyResource;
    }

    protected function getOriginalUserForTckUpdateAnyResource(): ?UserEntity
    {
        return $this->originalUserForTckUpdateAnyResource;
    }

    public function nullifyOriginalUserForTckUpdateAnyResource() : void
    {
        if (!$this->isUsingXFRM22ForTckUpdateAnyResource() || !$this->getNullifyUserForTckUpdateAnyResource())
        {
            return;
        }

        $this->getUpdate()->Resource->hydrateRelation('User', null);
    }

    public function restoreOriginalUserForTckUpdateAnyResource() : void
    {
        if (!$this->isUsingXFRM22ForTckUpdateAnyResource() || !$this->getNullifyUserForTckUpdateAnyResource())
        {
            return;
        }

        $this->getUpdate()->Resource->hydrateRelation('User', $this->getOriginalUserForTckUpdateAnyResource());
        $this->setNullifyUserForTckUpdateAnyResource(true);
    }

    public function afterInsert()
    {
        parent::afterInsert();

        $this->nullifyOriginalUserForTckUpdateAnyResource();
    }
}