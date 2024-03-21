<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;

class ActionCrudFlash extends ActionCrudModel {

    /**
     * @var string
     */
    public string $successMessage = 'Данные успешно сохранены';

    /**
     * @var string
     */
    public string $errorMessage = 'При создании записи произошла ошибка. Обратитесь в службу поддержки.';

    /**
     * @var bool
     */
    public bool $addFlashMessages = true;

    /**
     * @return void
     */
    protected function flashSuccess() {
        if ($this->addFlashMessages) Yii::$app->session->setFlash('success', $this->successMessage);
    }

    /**
     * @return void
     */
    protected function flashError() {
        if ($this->addFlashMessages) Yii::$app->session->setFlash('error', $this->errorMessage);
    }
}