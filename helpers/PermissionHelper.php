<?php

namespace kozlovsv\crud\helpers;

use kozlovsv\crud\classes\EventWithModel;
use kozlovsv\crud\permission\IPermissionInterface;
use yii\base\InvalidArgumentException;

class PermissionHelper
{
    const TYPE_ACTION_VIEW = 'view';
    const TYPE_ACTION_UPDATE = 'update';
    const TYPE_ACTION_DELETE = 'delete';

    /**
     * @param IPermissionInterface $model
     * @param string $typeAction
     * @return void
     */
    public static function  checkAccess(IPermissionInterface $model, string $typeAction)
    {
        $model->getPermission()->checkAccess($typeAction);
    }

    /**
     * @param EventWithModel $event
     * @return void
     */
    public static function checkAccessEvent($event)
    {
        $typeAction = (string)$event->data;
        if (!$event->model instanceof IPermissionInterface) throw new InvalidArgumentException("Model need IPermissionInterface");
        self::checkAccess($event->model, $typeAction);
    }

}