<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use TickTackk\UpdateAnyResource\Globals;
use XF\Mvc\Entity\Structure;
use XF\Phrase;
use XF\Entity\User as UserEntity;

/**
 * COLUMNS
 * @property int user_id
 * @property int user_id_
 * @property string username
 *
 * RELATIONS
 * @property UserEntity User
 */
class ResourceUpdate extends XFCP_ResourceUpdate
{
    /**
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canEdit(&$error = null)
    {
        try
        {
            Globals::$makeUseOfUpdateAnyPermission = $this;
            return parent::canEdit($error);
        }
        finally
        {
            Globals::$makeUseOfUpdateAnyPermission = null;
        }
    }

    /**
     * @param string      $type
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canDelete($type = 'soft', &$error = null)
    {
        $visitor = \XF::visitor();
        $resource = $this->Resource;

        if (!$visitor->user_id|| !$resource || $this->isDescription())
        {
            return false;
        }

        if ($type !== 'soft')
        {
            return $resource->hasPermission('hardDeleteAny');
        }

        if ($resource->hasPermission('deleteAny'))
        {
            return true;
        }

        return (
            $this->user_id === $visitor->user_id
            && $resource->hasPermission('updateOwn')
        );
    }

    /**
     * @return bool
     */
    public function canSendModeratorActionAlert()
    {
        $visitor = \XF::visitor();
        $resource = $this->Resource;

        return (
            $resource
            && $resource->canSendModeratorActionAlert()
            && $this->message_state === 'visible'
            && $this->user_id !== $visitor->user_id
        );
    }

    /**
     * @param Structure $structure
     *
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['user_id'] = ['type' => self::UINT, 'required' => true];
        $structure->columns['username'] = ['type' => self::STR, 'maxLength' => 50,
            'required' => 'please_enter_valid_name'
        ];
        $structure->relations['User'] = [
            'entity' => 'XF:User',
            'type' => self::TO_ONE,
            'conditions' => 'user_id',
            'primary' => true
        ];

        return $structure;
    }
}