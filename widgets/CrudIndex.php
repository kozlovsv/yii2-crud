<?php

namespace kozlovsv\crud\widgets;
use kozlovsv\crud\helpers\ModelPermission;

/**
 * Class ActiveForm
 */
class CrudIndex extends CrudIndexEmpty
{

    /**
     * @var array
     */
    public $actionColumnsBefore;

    /**
     * @var array
     */
    public $actionColumnsAfter;

    /**
     * @var array
     */
    public $rowOptions = [];


    /**
     * Массив настроек столбцов тблицы для GridView
     * @var array
     */
    public $columns;

    /**
     * Провайдер данных для таблицы GridView
     * @var \yii\data\ActiveDataProvider
     */
    public $dataProvider;

    public $viewName = '_index_table';

    /**
     * Отрисовка блока таблицы
     */
    protected function renderTable()
    {
        $grid = $this->renderGrid();
        echo $this->render($this->viewName, compact('grid'));
    }

    public static function defaultActionColumnsBefore($isModal, $tableName){
        return [
            [
                'class' => 'kozlovsv\crud\widgets\ActionColumn',
                'template' => '{view}',
                'isModal' => $isModal,
                'visible' => ModelPermission::canView($tableName),
            ],
        ];
    }

    private function normalizeActionColumnsBefore()
    {
        if (!isset($this->actionColumnsBefore)) {
            return self::defaultActionColumnsAfter($this->isModal, $this->searchModel->tableName());
        }
        return $this->actionColumnsBefore;
    }

    public static function defaultActionColumnsAfter($isModal, $tableName){
        return [
            [
                'class' => 'kozlovsv\crud\widgets\ActionColumn',
                'template' => '{update}',
                'isModal' => $isModal,
                'visible' => ModelPermission::canUpdate($tableName),
            ],
            [
                'class' => 'kozlovsv\crud\widgets\ActionColumn',
                'template' => '{delete}',
                'visible' => ModelPermission::canDelete($tableName),
            ],
        ];
    }

    private function normalizeActionColumnsAfter()
    {
        if (!isset($this->actionColumnsAfter)) {
            return self::defaultActionColumnsAfter($this->isModal, $this->searchModel->tableName());
        }
        return $this->actionColumnsAfter;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function renderGrid()
    {
        $actionColumnsBefore = $this->normalizeActionColumnsBefore();
        $actionColumnsAfter = $this->normalizeActionColumnsAfter();
        return GridView::widget([
            'dataProvider' => $this->dataProvider,
            'rowOptions' => $this->rowOptions,
            'columns' => array_merge($actionColumnsBefore, $this->columns, $actionColumnsAfter),
        ]);
    }
}