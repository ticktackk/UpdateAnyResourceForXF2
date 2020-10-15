<?php

namespace TickTackk\UpdateAnyResource\XFRM\Entity;

use XF\Mvc\Entity\Structure;
use XF\Phrase;
use XF\Entity\User as UserEntity;

/**
 * Class ResourceVersion
 *
 * @package TickTackk\UpdateAnyResource
 *
 * COLUMNS
 * @property int user_id
 * @property string username
 *
 * RELATIONS
 * @property UserEntity User
 */
class ResourceVersion extends XFCP_ResourceVersion
{
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

        if (!$visitor->user_id || !$resource)
        {
            return false;
        }

        if ($resource->current_version_id === $this->resource_version_id)
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