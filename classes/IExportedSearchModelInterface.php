<?php
namespace kozlovsv\crud\classes;

use yii\db\ActiveQuery;

interface IExportedSearchModelInterface extends ISearchModelInterface
{
    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveQuery
     */
    public function searchQuery($params);

    /**
     * @return array
     */
    public static function getDefaultSort();
}