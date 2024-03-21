<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\db\Exception;
use yii\web\Response;

class ActionCrudOperation extends ActionCrudFlash {
    /**
     * @param $model
     * @return string|Response
     * @throws Exception
     */
    protected function doRun($model)
    {
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save(false);
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
            $this->flashSuccess();
            return $this->goBack();
        }
        return $this->renderIfAjax($this->viewName, compact('model'));
    }
}