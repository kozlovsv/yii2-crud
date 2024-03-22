<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\db\ActiveRecord;

class ActionCrudCreate extends BaseCrudFormAction
{
    /**
     * @var string
     */
    public string $viewName = 'create';

    /**
     * @var string
     */
    public string $successMessage = 'Данные успешно сохранены';

    /**
     * @var string
     */
    public string $errorMessage = 'При добавлении записи произошла ошибка. Обратитесь в службу поддержки.';


    /**
     * Загружать значения по умолчанию при создании модели
     * @var bool
     */
    public $loadDefaultValue = true;

    /**
     * Загружать значения переданные через Get параметры
     * @var bool
     */
    public $loadGetValue = false;

    /**
     * @return ActiveRecord
     */
    protected function createModel()
    {
        $model = parent::createModel();
        if ($this->loadDefaultValue) $model->loadDefaultValues(true);
        if ($this->loadGetValue) $model->load(Yii::$app->request->get());
        return $model;
    }
}