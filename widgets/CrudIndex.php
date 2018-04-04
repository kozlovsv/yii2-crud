<?php

namespace kozlovsv\crud\widgets;
use kozlovsv\helpers\ModelPermission;
use kozlovsv\widgets\GridView;

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

    private function normalizeActionColumnsBefore()
    {
        if (!isset($this->actionColumnsBefore)) {
            return [
                [
                    'class' => 'kozlovsv\widgets\ActionColumn',
                    'template' => '{view}',
                    'isModal' => $this->isModal,
                ],
            ];
        }
        return $this->actionColumnsBefore;
    }

    private function normalizeActionColumnsAfter()
    {
        if (!isset($this->actionColumnsAfter)) {
            return [
                [
                    'class' => 'kozlovsv\widgets\ActionColumn',
                    'template' => '{update}',
                    'isModal' => $this->isModal,
                    'visible' => ModelPermission::canUpdate($this->searchModel->tableName()),
                ],
                [
                    'class' => 'kozlovsv\widgets\ActionColumn',
                    'template' => '{delete}',
                    'visible' => ModelPermission::canDelete($this->searchModel->tableName()),
                ],
            ];
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