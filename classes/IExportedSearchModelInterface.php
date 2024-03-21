<?php
namespace kozlovsv\crud\classes;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

interface IExportedSearchModelInterface
{
    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveQuery
     */
    public function searchQuery($params);


    /**
     * Поиск по параметрам фильтра
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = []);

    /**
     * @return array
     */
    public static function getDefaultSort();
}