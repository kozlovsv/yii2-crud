<?php

namespace kozlovsv\crud\widgets;

/**
 * Class ActiveForm
 */
class CrudForm extends CrudFormEmpty
{
    /**
     * Модель
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * Нормализовать  параметры
     * @return string
     */
    protected function normalizeIdForm()
    {
        if (!empty($this->idForm)) return $this->idForm;
        if (!empty($this->model)) return ($this->needPjax()? 'pjax-' : ''). $this->model->tableName() . '-form';
        return 'pjax-form';
    }

    /**
     * Отрисовка полей ввода. Для переопределения в классах потомках.
     * @param array $fields
     * @throws \Exception
     */
    protected function renderFields($fields)
    {
        parent::renderFields($fields);
        //echo $form->errorSummary($model);
        $first = true;
        foreach ($fields as $params) {
            if ($first) {
                $params['options'] = array_merge(isset($params['options'])? $params['options'] : [], ['autofocus' => true]);
                $first = false;
            }
            echo CrudField::widget([
                'model' => $this->model,
                'form' => $this->activeForm,
                'params' => $params,
            ]);
        }
    }
}