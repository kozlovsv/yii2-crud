<?php

namespace kozlovsv\crud\helpers;
use kozlovsv\crud\models\permission\IModelPermissionInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

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

    /**
     * Проверка разрешение на действие с моделью. Данная проверка это не RBACK доступ а проверка возможности конкретного
     * действия с конкретной моделью. Если проверка не пройдена, то выкидывается исключение ForbiddenHttpException
     * @param Model $model
     * @param string $actionName @see BaseModelPermission::checkAccess
     * @param bool $modelPermissionRequired Если данный параметр установлен в false, то функция не будет выбрасывать исключение, если проверяемая модель не реализует интерфейс IModelPermissionInterface
     * @throws InvalidConfigException
     */
    public static function checkPermission(Model $model, string $actionName = '', bool $modelPermissionRequired = true) {
        if ($model instanceof IModelPermissionInterface) {
            $model->getPermission()->checkAccess($actionName);
        } else {
            if ($modelPermissionRequired) throw new InvalidConfigException('CRUD Model must implements IModelPermissionInterface');
        }
    }
}
