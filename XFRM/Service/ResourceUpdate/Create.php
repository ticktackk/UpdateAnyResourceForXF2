<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceUpdate;

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
        $originalUserId = $resource->user_id;
        $originalUsername = $resource->username;

        try
        {
            $visitor = \XF::visitor();
            $resource->hydrateRelation('User', $visitor);
            $resource->setAsSaved('user_id', $visitor->user_id);
            $resource->setAsSaved('username', $visitor->username);

            return parent::getThreadReplyMessage();
        }
        finally
        {
            $resource->hydrateRelation('User', $originalUser);
            $resource->setAsSaved('user_id', $originalUserId);
            $resource->setAsSaved('username', $originalUsername);
        }
    }

    protected function afterResourceThreadReplied(PostEntity $post, $existingLastPostDate)
    {
        parent::afterResourceThreadReplied($post, $existingLastPostDate);

        $this->getUpdatePreparer()->restoreOriginalUserForTckUpdateAnyResource();
    }
}