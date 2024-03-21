<?php

namespace kozlovsv\crud\helpers;
use Yii;

/**
 * Проверка разрешения на действия с моделью
 * Class Permission
 * @package kozlovsv\helpers
 */
class ModelPermission
{
    /**
     * @return string
     */
    public static function getPermissionCategory($modelClassName)
    {
        return $modelClassName::tableName();
    }

    /**
     * Проверка разрешения на действия с моделью
     * @param string $permissionCategory
     * @param $permission
     * @return bool
     */
    public static function can($permissionCategory, $permission)
    {
        return Yii::$app->user->can("{$permissionCategory}.$permission");
    }

    /**
     * Разрешен просмотр
     * @param string $permissionCategory
     * @return bool
     */
    public static function canView($permissionCategory)
    {
        return self::can($permissionCategory, 'view');
    }

    /**
     * Разрешено создание новой записи
     * @param string $permissionCategory
     * @return bool
     */
    public static function canCreate($permissionCategory)
    {
        return self::can($permissionCategory, 'create');
    }

    /**
     * Разрешено редактирование
     * @param string $permissionCategory
     * @return bool
     */
    public static function canUpdate($permissionCategory)
    {
        return self::can($permissionCategory, 'update');
    }

    /**
     * Разрешено удаление
     * @param string $permissionCategory
     * @return bool
     */
    public static function canDelete($permissionCategory)
    {
        return self::can($permissionCategory, 'delete');
    }
}
