<?php

namespace TickTackk\UpdateAnyResource\XFRM\Notifier\ResourceUpdate;

use XF\Entity\User as UserEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\ResourceUpdate as ExtendedResourceUpdateEntity;

/**
 * @property ExtendedResourceUpdateEntity $update
 *
 * @version 1.1.1
 */
class ResourceWatch extends XFCP_ResourceWatch
{
    /**
     * @return bool
     */
    public function canNotify(UserEntity $user)
    {
        return parent::canNotify($user)
            && !$user->isIgnoring($this->update->team_user_id);
    }

    /**
     * @return bool
     */
    public function sendAlert(UserEntity $user)
    {
        /** @var ExtendedResourceUpdateEntity $update */
        $update = $this->update;

        return $this->basicAlert(
            $user, $update->tck_uar_user_id, $update->tck_uar_username, 'resource_update', $update->resource_update_id, 'insert'
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