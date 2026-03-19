<?php
namespace kozlovsv\crud\models\permission;

trait OwnModelPermissionTrait
{
    /**
     * @var OwnModelPermission
     */
    public $permission;

    /**
     * @return BaseModelPermission
     */
    public function getPermission() {
        if ($this->permission === null) {
            $this->permission = new OwnModelPermission($this);
        }
        return $this->permission;
    }
}