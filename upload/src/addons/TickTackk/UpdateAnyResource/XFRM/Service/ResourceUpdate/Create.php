<?php

namespace TickTackk\UpdateAnyResource\XFRM\Service\ResourceUpdate;

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
    protected $update;

    /**
     * Create constructor.
     *
     * @param \XF\App                   $app
     * @param \XFRM\Entity\ResourceItem $resource
     */
    public function __construct(\XF\App $app, \XFRM\Entity\ResourceItem $resource)
    {
        parent::__construct($app, $resource);

        if (!$this->update->user_id)
        {
            $visitor = \XF::visitor();

            if (!$visitor->user_id)
            {
                throw new \LogicException("Guests cannot add new update to resources.");
            }

            $this->update->user_id = $visitor->user_id;
            $this->update->username = $visitor->username;
        }
    }

    protected function getThreadReplyMessage()
    {
        $resource = $this->resource;
        $update = $this->update;

        $snippet = $this->app->bbCode()->render(
            $this->app->stringFormatter()->wholeWordTrim($update->message, 500),
            'bbCodeClean',
            'post',
            null
        );

        $phrase = \XF::phrase('xfrm_resource_thread_update', [
            'title' => $update->title_,
            'resource_title' => $resource->title_,
            'username' => $update->User ? $update->User->username : $update->username,
            'snippet' => $snippet,
            'resource_link' => $this->app->router('public')->buildLink('canonical:resources', $resource),
            'update_link' => $this->app->router('public')->buildLink('canonical:resources/update', $update)
        ]);

        return $phrase->render('raw');
    }
}