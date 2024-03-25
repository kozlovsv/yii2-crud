<?php

namespace kozlovsv\crud\helpers;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class FindOneModelHelper
{
    /**
     * @param $id
     * @param string $modelClassName
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function findOne($id, string $modelClassName): ActiveRecord
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var ActiveRecord $model */
        $model = $modelClassName::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $model;
    }


    /**
     * @param $id
     * @param string $modelClassName
     * @param string $actionName @see ModelPermission::checkPermission
     * @param bool $modelPermissionRequired @see ModelPermission::checkPermission
     * @return ActiveRecord
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public static function findOneAndCheckAccess($id, string $modelClassName, string $actionName = '', bool $modelPermissionRequired = true): ActiveRecord
    {
        $model = self::findOne($id, $modelClassName);
        ModelPermission::checkPermission($model, $actionName, $modelPermissionRequired);
        return $model;
    }
}