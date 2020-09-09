<?php
namespace kozlovsv\crud\helpers;


use Yii;
use yii\base\BaseObject;

class DBHelper extends BaseObject {
    /**
     * Пакетная вставка списка данных в таблицу
     * @param array $data Набор данных. Массив строк. Ключи в строках полностью должны совпадать с наименованием полей таблицы БД.
     * @param string $tableName Имя таблицы БД
     * @param bool $ignore Вставлять флаг IGNORE в INSERT INTO
     * @return int Количество сохраненных строк
     */
    public static function saveRows($data, $tableName, $ignore = false)
    {
        $cnt = 0;
        if (!$data) return 0;
        $columns = array_keys(reset($data));
        $chunkData = array_chunk($data, 300);
        foreach ($chunkData as $rows) {
            $sql = Yii::$app->db->queryBuilder->batchInsert($tableName, $columns, $rows);
            if ($ignore) $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $sql);
            $cnt += Yii::$app->db->createCommand($sql)->execute();
        }
        return $cnt;
    }

}