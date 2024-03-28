<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\Response;

class BaseCrudFormAction extends BaseCrudTransactionAction {

    use RenderIfAjaxTrait;

    /**
     * @var string
     */
    public string $viewName = '';

    /**
     * @param Model $model
     * @param mixed $id
     * @return string|Response
     */
    protected function doAction($model, $id)
    {
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            return parent::doAction($model, $id);
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