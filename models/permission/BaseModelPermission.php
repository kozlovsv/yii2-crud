<?php
namespace kozlovsv\crud\models\permission;

use yii\base\BaseObject;
use yii\base\Model;
use yii\web\ForbiddenHttpException;

class BaseModelPermission extends BaseObject
{
    public $errorMessage =  'Доступ к данной странице закрыт';

    /**
     * @var Model
     */
    public $model = null;

    public function __construct($model, $config = [])
    {
        $this->model = $model;
        parent::__construct($config);
    }

    /**
     * @param string $typeAction
     */
    public function checkAccess($typeAction = '') {
        $method = 'can' . ucfirst($typeAction);
        if (!$this->$method()) $this->forbidden();
    }

    /**
     * @return mixed
     * @throws \yii\web\ForbiddenHttpException
     */
    private function forbidden()
    {
        throw new ForbiddenHttpException($this->errorMessage);
    }
}