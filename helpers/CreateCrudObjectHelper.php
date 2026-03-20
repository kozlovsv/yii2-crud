<?php

namespace kozlovsv\crud\helpers;

use Exception;
use kozlovsv\crud\classes\BackRedirecter;
use kozlovsv\crud\classes\IBackRedirecrer;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * Класс помошник для создания CRUD моделей (новая запись в БД)
 */
class CreateCrudObjectHelper
{
    /**
     * Создание CRUD модели по имени класса для новой записи
     * @param string $modelClassName
     * @param bool $loadDefaultValue
     * @param bool $loadGetValue
     * @param array $params
     * @return ActiveRecord
     * @throws Exception
     */
    public static function createNewCrudModel(string $modelClassName, bool $loadDefaultValue, bool $loadGetValue, array $params = []): ActiveRecord
    {
        $model = Yii::createObject($modelClassName, $params);
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