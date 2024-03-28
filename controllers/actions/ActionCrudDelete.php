<?php

namespace kozlovsv\crud\controllers\actions;

use yii\db\ActiveRecord;
use yii\web\Response;

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

    /**
     * Performs an action on a model.
     *
     * @param ActiveRecord $model The model to perform the action on.
     * @param mixed $id The ID of the model.
     * @return Response The result of the action. Either a success message or a redirect response.
     */
    protected function doAction($model, $id) {
        if ($model->delete()) $this->setFlashSuccess($this->successMessage);
        return $this->goBackSuccess($id);
    }
}