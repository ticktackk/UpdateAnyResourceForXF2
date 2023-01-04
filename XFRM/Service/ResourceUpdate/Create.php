<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceUpdate;

use XF\App as BaseApp;
use XFRM\Entity\ResourceItem as ResourceItemEntity;
use XF\Entity\Post as PostEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;
use TickTackk\UpdateAnyResource\XFRM\Service\ResourceUpdate\Preparer as ExtendedResourceUpdatePreparerSvc;

/**
 * @method ExtendedResourceUpdateEntity getUpdate()
 * @method ExtendedResourceUpdatePreparerSvc getUpdatePreparer()
 */
class Create extends XFCP_Create
{
    protected function _save()
    {
        try
        {
            $this->getUpdatePreparer()->setNullifyUserForTckUpdateAnyResource(true);

            return parent::_save();
        }
        finally
        {
            $this->getUpdatePreparer()->restoreOriginalUserForTckUpdateAnyResource();
        }
    }

    /**
     * @return string
     */
    protected function getThreadReplyMessage()
    {
        $resource = $this->getResource();
        $originalUser = $resource->User;

        try
        {
            $resource->hydrateRelation('User', \XF::visitor());

            return parent::getThreadReplyMessage();
        }
        finally
        {
            $resource->hydrateRelation('User', $originalUser);
        }
    }

    protected function afterResourceThreadReplied(PostEntity $post, $existingLastPostDate)
    {
        parent::afterResourceThreadReplied($post, $existingLastPostDate);

        $this->getUpdatePreparer()->restoreOriginalUserForTckUpdateAnyResource();
    }
}