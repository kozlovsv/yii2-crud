<?php

namespace kozlovsv\crud\controllers\actions;

class ActionCrudDelete extends BaseCrudAction
{
    /**
     * @var string
     */
    public string $successMessage = 'Запись удалена';

    /**
     * @var string
     */
    public string $errorMessage = 'Запись не может быть удалена, имеются связанные данные';

    protected function doAction($model) {
        if ($model->delete()) $this->setFlashSuccess($this->successMessage);
        return $this->goBackSuccess();
    }
}