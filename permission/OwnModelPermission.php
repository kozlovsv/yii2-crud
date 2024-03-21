<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace kozlovsv\crud\permission;


use kozlovsv\crud\classes\IOwnInterface;
use kozlovsv\crud\helpers\ModelPermission;
use yii\base\Model;

class OwnModelPermission extends BasePermission
{
    /**
     * @var Model | IOwnInterface
     */
    public $model;

    public $needCache = true;
    private $_own = null;

    /**
     * @return string
     */
    public function getPermissionCategoryName() {
        return $this->model::tableName();
    }

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
     * @return bool
     */
    public function canView()
    {
        if (!ModelPermission::canView($this->getPermissionCategoryName())) return false;
        return $this->own();
    }

    /**
     * @return bool
     */
    public function canUpdate()
    {
        if (!ModelPermission::canUpdate($this->getPermissionCategoryName())) return false;
        return $this->own();
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        if (!ModelPermission::canDelete($this->getPermissionCategoryName())) return false;
        return $this->own();
    }
}