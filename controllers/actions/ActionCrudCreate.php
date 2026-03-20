<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\helpers\CreateCrudObjectHelper;
use yii\base\Model;

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
     * @return Model
     */
    protected function createModel(): Model
    {
        return CreateCrudObjectHelper::createNewCrudModel($this->modelClassName, $this->loadDefaultValue, $this->loadGetValue);
    }
}