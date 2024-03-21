<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\helpers\PermissionHelper;

abstract class ActionCrudUpdateWithPermission extends ActionCrudUpdate
{
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_AFTER_FIND_MODEL, [PermissionHelper::class,  'checkAccessEvent', PermissionHelper::TYPE_ACTION_UPDATE]);
    }
}