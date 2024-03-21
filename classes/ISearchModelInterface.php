<?php
namespace kozlovsv\crud\classes;

use yii\data\ActiveDataProvider;

interface ISearchModelInterface
{
    /**
     * Поиск по параметрам фильтра
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = []);
}