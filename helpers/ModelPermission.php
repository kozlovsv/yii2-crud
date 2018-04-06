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
     * Проверка разрешения на действия с моделью
     * @param string $tablelName
     * @param $permission
     * @return bool
     */
    public static function can($tablelName, $permission)
    {
        return Yii::$app->user->can("{$tablelName}.$permission");
    }

    /**
     * Разрешен просмотр
     * @param string $tablelName
     * @return bool
     */
    public static function canView($tablelName)
    {
        return self::can($tablelName, 'view');
    }

    /**
     * Разрешено создание новой записи
     * @param string $tablelName
     * @return bool
     */
    public static function canCreate($tablelName)
    {
        return self::can($tablelName, 'create');
    }

    /**
     * Разрешено редактирование
     * @param string $tablelName
     * @return bool
     */
    public static function canUpdate($tablelName)
    {
        return self::can($tablelName, 'update');
    }

    /**
     * Разрешено удаление
     * @param string $tablelName
     * @return bool
     */
    public static function canDelete($tablelName)
    {
        return self::can($tablelName, 'delete');
    }
}
