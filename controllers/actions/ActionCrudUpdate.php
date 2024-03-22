<?php

namespace kozlovsv\crud\controllers\actions;

abstract class ActionCrudUpdate extends BaseCrudFormAction
{
    /**
     * @var string
     */
    public string $viewName = 'update';

    /**
     * @var string
     */
    public string $successMessage = 'Данные успешно сохранены';

    /**
     * @var string
     */
    public string $errorMessage = 'При сохранении записи произошла ошибка. Обратитесь в службу поддержки.';
}