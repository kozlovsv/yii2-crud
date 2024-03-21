<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\Response;


class ActionCrudCreate extends ActionCrudOperation
{
    const EVENT_AFTER_CREATE_MODEL = 'afterCreateModel';

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
     * @return Response|string
     */
    public function run()
    {
        try {
            $model = $this->createModel();
            return $this->doRun($model);
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goBackAfterError();
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            $this->flashError();
            return $this->goBackAfterError();
        }
    }

    /**
     * @return ActiveRecord
     */
    protected function createModel()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClassName();
        if ($this->loadDefaultValue) $model->loadDefaultValues(true);
        if ($this->loadGetValue) $model->load(Yii::$app->request->get());
        return $model;
    }
}