<?php

namespace kozlovsv\crud\helpers;

use Exception;
use kozlovsv\crud\classes\BackRedirecter;
use kozlovsv\crud\classes\IBackRedirecrer;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * Класс помошник для создания CRUD моделей (новая запись в БД)
 */
class CreateCrudObjectHelper
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

    public static function createNewModel(string $modelClassName, bool $loadDefaultValue, bool $loadGetValue, array $params = []): ActiveRecord
    {
        $model = self::createSimpleModel($modelClassName, $params);
        if (!$model instanceof ActiveRecord) throw new Exception('Created model must be instance of ActiveRecord');
        if ($loadDefaultValue) $model->loadDefaultValues(true);
        if ($loadGetValue) $model->load(Yii::$app->request->get());
        return $model;
    }

    public static function createRedirecter(Controller $controller, $config = [])
    {
        if ($config instanceof IBackRedirecrer) return $config;
        if (is_array($config)) {
            $config = array_merge(
                [
                    'class' => BackRedirecter::class,
                ],
                $config
            );
        }
        return Yii::createObject($config, ['controller' => $controller]);
    }

}