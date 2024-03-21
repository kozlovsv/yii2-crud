<?php

namespace kozlovsv\crud\models\permission;


use kozlovsv\crud\classes\IOwnInterface;
use yii\base\Model;

class OwnModelModelPermission extends BaseModelPermission
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
    public function own() {
        if ($this->needCache && !empty($this->_own)) return $this->_own;
        $own = $this->model->own();
        if ($this->needCache) $this->_own = $own;
        return $own;
    }

    /**
     * @param string $typeAction
     */
    public function checkAccess($typeAction = '') {
        //Сначала проверяем общий доступ. А потом уже специальный по правилам.
        parent::checkAccess('access');
        if ($typeAction) parent::checkAccess($typeAction);
    }

    /**
     * @return bool
     */
    public function canAccess()
    {
        return $this->own();
    }
}