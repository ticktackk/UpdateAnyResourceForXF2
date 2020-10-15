<?php

namespace TickTackk\UpdateAnyResource\XFRM\Notifier\ResourceUpdate;

/**
 * Class ResourceUpdate
 *
 * @package TickTackk\UpdateAnyResource
 */
class ResourceWatch extends XFCP_ResourceWatch
{
    /**
     * @param \XF\Entity\User $user
     *
     * @return bool
     */
    public function canNotify(\XF\Entity\User $user)
    {
        if (!$this->isApplicable)
        {
            return false;
        }

        /** @var \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate $update */
        $update = $this->update;

        return !($user->user_id === $update->user_id_ || $user->isIgnoring($update->user_id_));
    }

    /**
     * @param \XF\Entity\User $user
     *
     * @return bool
     */
    public function sendAlert(\XF\Entity\User $user)
    {
        /** @var \TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate $update */
        $update = $this->update;

        return $this->basicAlert(
            $user, $update->user_id, $update->username, 'resource_update', $update->resource_update_id, 'insert'
        );
    }

    /**
     * @param \XF\Entity\User $user
     *
     * @return bool
     */
    public function sendEmail(\XF\Entity\User $user)
    {
        if (!$user->email || $user->user_state !== 'valid')
        {
            return false;
        }

        $update = $this->update;

        $params = [
            'update' => $update,
            'resource' => $update->Resource,
            'category' => $update->Resource->Category,
            'receiver' => $user
        ];

        $template = $this->getWatchEmailTemplateName();

        $this->app()->mailer()->newMail()
            ->setToUser($user)
            ->setTemplate($template, $params)
            ->queue();

        return true;
    }
}