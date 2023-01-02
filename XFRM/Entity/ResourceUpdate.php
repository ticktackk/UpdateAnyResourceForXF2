<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use TickTackk\UpdateAnyResource\Globals;
use TickTackk\UpdateAnyResource\XFRM\Entity\XF21\ResourceItem as XF21ResourceItemEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\XF22\ResourceItem as XF22ResourceItemEntity;
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
 * @property ResourceItemTrait|XF21ResourceItemEntity|XF22ResourceItemEntity $Resource
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
        $resource = $this->Resource;
        if (!$resource)
        {
            return false;
        }

        return $resource->runActionForTckUpdateAnyResource($this->user_id, function () use($error)
        {
            return parent::canEdit($error);
        });
    }

    /**
     * @param string      $type
     * @param Phrase|null $error
     *
     * @return bool
     */
    public function canDelete($type = 'soft', &$error = null)
    {
        $resource = $this->Resource;
        if (!$resource)
        {
            return false;
        }

        return $resource->runActionForTckUpdateAnyResource($this->user_id, function () use($type, $error)
        {
            return parent::canDelete($type, $error);
        });
    }

    /**
     * @return bool
     */
    public function canSendModeratorActionAlert()
    {
        return (
            parent::canSendModeratorActionAlert()
            && (\XF::visitor()->user_id !== $this->user_id)
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