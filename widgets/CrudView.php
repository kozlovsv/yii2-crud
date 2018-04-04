<?php

namespace kozlovsv\crud\widgets;
use yii\widgets\DetailView;

/**
 * Class ActiveForm
 */
class CrudView extends CrudViewEmpty
{

    /**
     * Массив атрибутов с полями для отображения, передается в виджет DetailView attributes.
     * @var array
     */
    public $attributes = [];


    /**
     *
     */
    public function run()
    {
        $this->renderDetailView();
        parent::run();
    }

    /**
     * Нормализовать список значений
     * @return array
     */
    protected function normalizeAttributes()
    {
        return !empty($this->attributes) ? $this->attributes : [];
    }

    /**
     * @throws \Exception
     */
    protected function renderDetailView()
    {
        $attributes = $this->normalizeAttributes();
        echo DetailView::widget([
            'model' => $this->model,
            'attributes' => $attributes
        ]);
    }
}