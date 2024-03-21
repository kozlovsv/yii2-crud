<?php

namespace kozlovsv\crud\helpers;

use kozlovsv\crud\models\permission\IModelPermissionInterface;
use yii\base\Event;
use yii\base\InvalidArgumentException;

class ModelPermissionHelper
{
    /**
     * @param IModelPermissionInterface $model
     * @param string $typeAction
     * @return void
     */
    public static function checkAccess(IModelPermissionInterface $model, string $typeAction)
    {
        $model->getPermission()->checkAccess($typeAction);
    }

    /**
     * @param Event $event
     * @return void
     */
    public static function checkAccessEvent($event)
    {
        $typeAction = (string)$event->data;
        if (!($event->sender instanceof IModelPermissionInterface)) throw new InvalidArgumentException("Event sender must be instance of PermissionInterface");
        self::checkAccess($event->sender, $typeAction);
    }

}