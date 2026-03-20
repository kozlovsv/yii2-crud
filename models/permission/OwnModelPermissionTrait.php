<?php
namespace kozlovsv\crud\models\permission;

trait OwnModelPermissionTrait
{
    /**
     * @var OwnModelPermission
     */
    private $_permission;

    /**
     * @return BaseModelPermission
     */
    public function getPermission() {
        if ($this->_permission === null) {
            $this->_permission = new OwnModelPermission($this);
        }
        return $this->_permission;
    }
}