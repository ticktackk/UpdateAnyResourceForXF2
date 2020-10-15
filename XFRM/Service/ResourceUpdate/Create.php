<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceUpdate;

use XF\App as BaseApp;
use XFRM\Entity\ResourceItem as ResourceItemEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;

/**
 * @method ExtendedResourceUpdateEntity getUpdate()
 */
class Create extends XFCP_Create
{
    public function __construct(BaseApp $app, ResourceItemEntity $resource)
    {
        parent::__construct($app, $resource);

        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            throw new \LogicException('Guests cannot add new update to resources.');
        }

        $update = $this->getUpdate();
        $update->user_id = $visitor->user_id;
        $update->username = $visitor->username;
    }

    /**
     * @return string
     */
    protected function getThreadReplyMessage()
    {
        $resource = $this->getResource();
        $update = $this->getUpdate();
        $app = $this->app;
        $router = $app->router('public');

        $snippet = $app->bbCode()->render(
            $app->stringFormatter()->wholeWordTrim($update->message, 500),
            'bbCodeClean',
            'post',
            null
        );

        /** @noinspection PhpUndefinedFieldInspection */
        $phrase = \XF::phrase('xfrm_resource_thread_update', [
            'title' => $update->title_,
            'resource_title' => $resource->title_,
            'username' => $update->User ? $update->User->username : $update->username,
            'snippet' => $snippet,
            'resource_link' => $router->buildLink('canonical:resources', $resource),
            'update_link' => $router->buildLink('canonical:resources/update', $update)
        ]);

        return $phrase->render('raw');
    }
}