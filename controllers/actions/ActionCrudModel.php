<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\classes\FindOneModel;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class ActionCrudModel extends ActionCrudBase
{
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
        return FindOneModel::find($id, $this->modelClassName);
    }

}