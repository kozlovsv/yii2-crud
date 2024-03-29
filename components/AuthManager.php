<?php
namespace kozlovsv\crud\components;

use Yii;
use yii\rbac\DbManager;

class AuthManager extends DbManager
{

    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        $key = self::getCacheKey($userId);

        if (Yii::$app->cache->exists($key)) {
            $assignments = Yii::$app->cache->get($key);
        } else {
            $assignments = parent::getAssignments($userId);
            Yii::$app->cache->add($key, $assignments);
        }

        return $assignments;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getCacheKey($id)
    {
        return Yii::$app->id . '-assignments-' . $id;
    }

    /**
     * @param $id
     */
    public static function clearCache($id) {
        Yii::$app->cache->delete(self::getCacheKey($id));
    }
}