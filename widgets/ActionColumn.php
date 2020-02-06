<?php
namespace kozlovsv\crud\widgets;

use yii\bootstrap\Html;


/**
 * Колонка действий над записью
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * @var string
     */
    public $template = '{update} {view} {delete}';

    /**
     * @var array
     */
    public $contentOptions = ['class' => 'tbl-action'];

    /**
     * @var bool
     */
    public $isModal = true;

    /**
     * @var string
     */
    public $target;

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url) {
                $options = array_merge([
                    'title' => 'Просмотр',
                    'aria-label' => 'Просмотр',
                    'data-pjax' => 0,
                    'data-modal' => $this->isModal ? 1 : 0,
                    'target' => $this->target,
                        ], $this->buttonOptions);
                return Html::a(Html::icon('search'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url) {
                $options = array_merge([
                    'title' => 'Изменить',
                    'aria-label' => 'Изменить',
                    'data-pjax' => 0,
                    'data-modal' => $this->isModal ? 1 : 0,
                    'target' => $this->target,
                        ], $this->buttonOptions);
                return Html::a(Html::icon('pencil'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url) {
                $options = array_merge([
                    'title' => 'Удалить',
                    'aria-label' => 'Удалить',
                    'data-confirm' => 'Удалить запись?',
                    'data-pjax' => 0,
                    'data-method' => 'post',
                        ], $this->buttonOptions);
                return Html::a(Html::icon('remove'), $url, $options);
            };
        }
    }
}