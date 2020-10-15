<?php

namespace TickTackk\UpdateAnyResource\XFRM\Notifier\ResourceUpdate;

use XF\Entity\User as UserEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;

class ResourceWatch extends XFCP_ResourceWatch
{
    /**
     * @return bool
     */
    public function canNotify(UserEntity $user)
    {
        if (!$this->isApplicable)
        {
            return false;
        }

        /** @var ExtendedResourceUpdateEntity $update */
        $update = $this->update;

        return !($user->user_id === $update->user_id_ || $user->isIgnoring($update->user_id_));
    }

    /**
     * @return bool
     */
    public function sendAlert(UserEntity $user)
    {
        /** @var ExtendedResourceUpdateEntity $update */
        $update = $this->update;

        return $this->basicAlert(
            $user, $update->user_id, $update->username, 'resource_update', $update->resource_update_id, 'insert'
        );
    }

    /**
     * @return bool
     */
    public function sendEmail(UserEntity $user)
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