<?php
namespace kozlovsv\crud\permission;

interface IPermissionInterface
{
    /**
     * @return BasePermission
     */
    public function getPermission();
}