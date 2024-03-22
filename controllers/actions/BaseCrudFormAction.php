<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\Response;

class BaseCrudFormAction extends BaseCrudTransactionAction {

    use RenderIfAjaxTrait;

    /**
     * @var string
     */
    public string $viewName = '';

    /**
     * @param $model
     * @return string|Response
     * @throws Exception
     */
    protected function doAction($model)
    {
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            return parent::doAction($model);
        }
        return $this->renderIfAjax($this->viewName, compact('model'));
    }

    /**
     * @param ActiveRecord $model
     * @return bool
     */
    protected function doActionModel($model): bool {
        return $model->save();
    }
}