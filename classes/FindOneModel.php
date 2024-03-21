<?php

namespace kozlovsv\crud\classes;

use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class FindOneModel
{
    /**
     * @param $id
     * @param string $modelClassName
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function find($id, string $modelClassName)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var ActiveRecord $model */
        $model = $modelClassName::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $model;
    }
}