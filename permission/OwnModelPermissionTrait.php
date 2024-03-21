<?php
namespace kozlovsv\crud\permission;

trait OwnModelPermissionTrait
{
    /**
     * @var OwnModelPermission
     */
    public $permission;

    /**
     * @return BasePermission
     */
    public function getPermission() {
        return $this->permission;
    }

    public function init() {
        /** @noinspection PhpUndefinedClassInspection */
        parent::init();
        $this->permission = new OwnModelPermission($this);
    }
}