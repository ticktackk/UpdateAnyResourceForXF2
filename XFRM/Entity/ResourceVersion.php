<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use TickTackk\UpdateAnyResource\XFRM\Entity\XF22\ResourceItem as XF22ResourceItemEntity;
use TickTackk\UpdateAnyResource\XFRM\Entity\XF21\ResourceItem as XF21ResourceItemEntity;
use XF\Mvc\Entity\Structure;
use XF\Entity\User as UserEntity;

/**
 * COLUMNS
 * @property int user_id
 * @property string username
 *
 * RELATIONS
 * @property UserEntity User
 * @property ResourceItemTrait|XF21ResourceItemEntity|XF22ResourceItemEntity $Resource
 *
 * @version 1.1.1
 */
class ResourceVersion extends XFCP_ResourceVersion
{
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