<?php

namespace kozlovsv\crud\widgets;
use yii\bootstrap\Html;
use kozlovsv\helpers\ModelPermission;
use Yii;
use yii\base\Widget;

/**
 * Class ActiveForm
 */
class CrudViewEmpty extends Widget
{
    /**
     * Модель
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * Наименование класса основного контейнера DIV формы
     * @var string
     */
    public $divClass = 'form-view';

    /**
     * Заголовок формы
     * @var string
     */
    public $title = '';

    /**
     * Путь до файла шаблона View
     * @var string
     */
    public $viewName = 'view';

    /**
     * Массив кнопок верхней панели. Слева.
     * @var array
     */
    public $buttonsLeft = [];

    /**
     * Массив кнопок верхней панели. Справа.
     * @var array
     */
    public $buttonsRight = [];

    public function init(){
        ob_start();
        ob_implicit_flush(false);
    }

    public function run()
    {
        $content = ob_get_clean();
        $divClass = $this->normalizeDivClass();
        $buttonsLeft = $this->normalizeButtonsLeft();
        $buttonsRight = $this->normalizeButtonsRight();
        echo $this->render('view', ['model' => $this->model, 'divClass' => $divClass, 'title' => $this->title, 'content' => $content, 'buttonsLeft' => $buttonsLeft, 'buttonsRight' => $buttonsRight]);
    }

    protected function normalizeDivClass()
    {
        if (!empty($this->divClass)) return $this->divClass;
        if (!empty($this->model)) return $this->model->tableName() . '-view';
        return 'div-view';
    }

    protected function normalizeButtonsLeft()
    {
        if (!empty($this->buttonsLeft)) return $this->buttonsLeft;
        return self::standartButtonsLeft($this->model);
    }

    protected function normalizeButtonsRight()
    {
        if (!empty($this->buttonsRight)) return $this->buttonsRight;
        return self::standartButtonsRight($this->model);
    }


    /**
     * @param \yii\db\ActiveRecord $model
     * @param bool $isModalEdit
     * @return array
     */
    public static function standartButtonsLeft($model, $isModalEdit = true)
    {
        $arr = [];
        if (ModelPermission::canUpdate($model->tableName())) {
            $arr[] = Html::a(Html::icon('pencil'), ['update', 'id' => $model->getPrimaryKey()],
                [ 'class' => 'btn btn-primary', 'data-modal' => $isModalEdit? 1 : 0]);
        }
        $arr[] = Html::a('Отмена', ['index'], ['class' => 'btn btn-default form-cancel']);
        return $arr;
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    public static function standartButtonsRight($model)
    {
        $arr = [];
        if (ModelPermission::canDelete($model->tableName())) {
            $arr[] = Html::a(Html::icon('trash'), ['delete', 'id' => $model->getPrimaryKey()], [
                'class' => 'btn btn-danger pull-right',
                'data' => [
                    'confirm' => 'Удалить запись?',
                    'method' => 'post',
                ],
            ]);
        }
        return $arr;
    }
}