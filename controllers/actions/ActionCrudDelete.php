<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

abstract class ActionCrudDelete extends ActionCrudFlash
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
     * @return Response
     */
    public function run($id)
    {
        try {
            $model = $this->findModel($id);
            if ($model->delete()) $this->flashSuccess();
            return $this->goBack();
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goBackAfterError();
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            $this->flashError();
            return $this->goBackAfterError();
        }
    }
}