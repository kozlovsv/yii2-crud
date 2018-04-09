<?php

namespace kozlovsv\crud\widgets;

use kozlovsv\crud\helpers\ModelPermission;
use kozlovsv\crud\helpers\ReturnUrl;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\helpers\Url;


/**
 * Верхняя панель с кнопками в форме просмотра записи круд(VIEW)
 */
class ViewTopPanel extends Widget
{
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

    /**
     * Модель
     * @var \yii\db\ActiveRecord
     */
    public $model;

    /**
     * Флаг, который указывает отрабатывать ли нажатие кнопок в панели в диалоговом режиме или обычном.
     * @var bool
     */
    public $isModal = true;

    public $buttonsContainerOptions = ['class' => 'form-group', 'style' => 'margin-bottom: 10px'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
    }

    public function run()
    {
        echo Html::beginTag('div', ['' => $this->buttonsContainerOptions]);
        $this->renderButtons($this->buttonsLeft, 'pull-left');
        $this->renderButtons($this->buttonsRight, 'pull-right');
        echo Html::endTag('div');
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @param bool $isModalEdit
     * @return array
     */
    public static function defaultButtonsLeft($model, $isModalEdit = true)
    {
        $arr = [];
        if (ModelPermission::canUpdate($model->tableName())) {
            $arr[] = Html::a(Html::icon('pencil'), ['update', 'id' => $model->getPrimaryKey(), ReturnUrl::REQUEST_PARAM_NAME => Url::to(['view', 'id' => $model->getPrimaryKey()])],
                ['class' => 'btn btn-primary', 'data-modal' => $isModalEdit ? 1 : 0]);
        }
        $arr[] = Html::a('Отмена', ReturnUrl::getBackUrl(), ['class' => 'btn btn-default form-cancel']);
        return $arr;
    }

    /**
     * @param \yii\db\ActiveRecord $model
     * @return array
     */
    public static function defaultButtonsRight($model)
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

    protected function initDefaultButtons()
    {
        if ($this->buttonsLeft == null) $this->buttonsLeft = self::defaultButtonsLeft($this->model, $this->isModal);
        if ($this->buttonsRight == null) $this->buttonsRight = self::defaultButtonsRight($this->model);
    }

    protected function renderButtons($buttons, $className)
    {
        echo Html::beginTag('div', ['class' => $className]);
        foreach ($buttons as $button) {
            echo Html::beginTag('div', ['class' => 'btn-group']);
            echo $button;
            echo Html::endTag('div');
        }
        echo Html::endTag('div');
    }
}