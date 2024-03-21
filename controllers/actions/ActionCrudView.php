<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

abstract class ActionCrudView extends ActionCrudModel
{
    /**
     * @var string
     */
    public string $viewName = 'view';

    /**
     * @return Response
     */
    public function run($id)
    {
        try {
            $model = $this->findModel($id);
            return $this->renderIfAjax($this->viewName, compact('model'));
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goBack();
        }
    }
}