<?php

namespace kozlovsv\crud\models\permission;


use kozlovsv\crud\models\IOwnInterface;
use yii\db\ActiveRecord;

class OwnModelPermission extends BaseModelPermission
{
    /**
     * @var ActiveRecord | IOwnInterface
     */
    public $model;

    public $needCache = true;
    private $_own = null;


    /**
     * @return bool
     */
    public function own()
    {
        if (!$this->needCache) return $this->model->own();
        if (is_null($this->_own)) {
            $this->_own = $this->model->own();
        }
        return $this->_own;
    }


    /**
     * @return bool
     */
    public function canView(): bool
    {
        return $this->own();
    }
}