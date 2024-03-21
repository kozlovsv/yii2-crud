<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\classes\EventWithModel;
use kozlovsv\crud\classes\FindOneModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class ActionCrudModel extends ActionCrudBase
{
    const EVENT_AFTER_FIND_MODEL = 'afterFindModel';

    /**
     * @var string
     */
    public string $modelClassName = '';

    public function init()
    {
        if (empty($this->modelClassName))
            throw new InvalidConfigException('The "modelClassName" config is required.');
        parent::init();
    }

    /**
     * @param $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = FindOneModel::find($id, $this->modelClassName);
        $this->afterFindModel($model);
        return $model;
    }

    /**
     * @param $model
     * @return void
     */
    protected function afterFindModel($model)
    {
        $this->trigger(self::EVENT_AFTER_FIND_MODEL, new EventWithModel(['model' => $model]));
    }
}