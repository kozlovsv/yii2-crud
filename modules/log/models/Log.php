<?php

namespace kozlovsv\crud\modules\log\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\log\Logger;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 */
class Log extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'level' => 'Уровень',
            'category' => 'Категория',
            'levelLabel' => 'Уровень',
            'log_time' => 'Время',
            'prefix' => 'Префикс',
            'message' => 'Сообщение',
        ];
    }

    /**
     * @return array
     */
    public static function categoryMap()
    {
        $items = self::find()->select('category')->distinct()->orderBy('category')->asArray()->all();
        return ArrayHelper::map($items, 'category', 'category');
    }

    /**
     * @return array
     */
    public static function levelMap()
    {
        return [
            Logger::LEVEL_ERROR => 'Ошибка',
            Logger::LEVEL_WARNING => 'Предупреждение',
            Logger::LEVEL_INFO => 'Уведомление',
            Logger::LEVEL_TRACE => 'Трассировка',
            Logger::LEVEL_PROFILE_BEGIN => 'Профайлинг начало',
            Logger::LEVEL_PROFILE_END => 'Профайлинг конец',
            Logger::LEVEL_PROFILE => 'Профайл',
        ];
    }

    /**
     * @return int|mixed
     */
    public function getCategoryLabel()
    {
        $map = self::categoryMap();

        return isset($map[$this->category]) ? $map[$this->category] : $this->category;
    }

    /**
     * @return int|mixed
     */
    public function getLevelLabel()
    {
        $map = self::levelMap();

        return isset($map[$this->level]) ? $map[$this->level] : $this->level;
    }
}