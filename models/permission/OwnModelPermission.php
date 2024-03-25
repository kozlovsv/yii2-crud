<?php

namespace kozlovsv\crud\models\permission;


use kozlovsv\crud\models\IOwnInterface;
use yii\base\Model;

class OwnModelPermission extends BaseModelPermission
{
    /**
     * @var Model | IOwnInterface
     */
    public $model;

    public $needCache = true;
    private $_own = null;


    /**
     * @return bool
     */
    public function own()
    {
        if ($this->needCache && !empty($this->_own)) return $this->_own;
        $own = $this->model->own();
        if ($this->needCache) $this->_own = $own;
        return $own;
    }


    /**
     * @return bool
     */
    protected function checkCommonAccess(): bool
    {
        return $this->own();
    }
}