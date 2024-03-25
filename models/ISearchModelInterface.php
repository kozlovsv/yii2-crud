<?php
namespace kozlovsv\crud\models;

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