<?php
namespace kozlovsv\crud\widgets;

use yii\data\ActiveDataProvider;

class GridView extends \yii\grid\GridView
{

    /** Размер страницы по умолчанию */
    const DEFAULT_PAGE_SIZE = 100;

    /**
     * Опции заголовков таблицы
     * @var array
     */
    public $headerRowOptions = ['class' => 'sort-header'];

    public $layout = "{items}\n<div class='navbar-fixed-bottom'><div class='container'>{summary}\n{pager}</div></div>";

    /**
     * @init
     */
    public function init()
    {
        if ($this->dataProvider instanceof ActiveDataProvider && $this->dataProvider->pagination) {
            $this->dataProvider->pagination->pageSize = self::DEFAULT_PAGE_SIZE;
        }
        parent::init();
    }
}
