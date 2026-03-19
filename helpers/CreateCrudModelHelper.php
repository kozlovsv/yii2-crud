<?php

namespace kozlovsv\crud\helpers;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Класс помошник для создания CRUD моделей (новая запись в БД)
 */
class CreateCrudModelHelper
{
    /**
     * Создание обычной модели по имени класса
     * @param string $modelClassName
     * @param array $params
     * @return Model
     * @throws InvalidConfigException
     */
    public static function createSimpleModel(string $modelClassName, array $params = []): Model
    {
        $model = Yii::createObject($modelClassName, $params);
        if (!$model instanceof Model) throw new Exception('Created model must be instance of Model');
        return $model;
    }

    public static function createNewModel(string $modelClassName, bool $loadDefaultValue, bool $loadGetValue, array $params = []): ActiveRecord {
        $model = self::createSimpleModel($modelClassName, $params);
        if (!$model instanceof ActiveRecord) throw new Exception('Created model must be instance of ActiveRecord');
        if ($loadDefaultValue) $model->loadDefaultValues(true);
        if ($loadGetValue) $model->load(Yii::$app->request->get());
        return $model;
    }

}