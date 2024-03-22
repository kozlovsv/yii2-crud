<?php
namespace kozlovsv\crud\models\permission;

use kozlovsv\crud\helpers\ModelPermissionHelper;
use yii\db\BaseActiveRecord;

/**
 * @method on(string $EVENT_AFTER_FIND, string[] $array)
 */
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
        $this->on(BaseActiveRecord::EVENT_AFTER_FIND, [ModelPermissionHelper::class, 'checkAccessEvent', 'access']);
    }
}