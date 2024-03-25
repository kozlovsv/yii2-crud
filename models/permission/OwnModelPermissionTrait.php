<?php
namespace kozlovsv\crud\models\permission;

use kozlovsv\crud\helpers\ModelPermissionHelper;
use yii\db\BaseActiveRecord;

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
        return $this->permission;
    }

    public function init() {
        /** @noinspection PhpUndefinedClassInspection */
        parent::init();
        $this->permission = new OwnModelPermission($this);
    }
}