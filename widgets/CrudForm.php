<?php

namespace kozlovsv\crud\widgets;

/**
 * Class ActiveForm
 */
class CrudForm extends CrudFormEmpty
{
    /**
     * Отрисовка полей ввода. Для переопределения в классах потомках.
     * @param array $fields
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