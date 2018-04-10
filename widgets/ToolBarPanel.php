<?php

namespace kozlovsv\crud\widgets;

use yii\bootstrap\Html;
use yii\bootstrap\Widget;


/**
 * Панель инструментов. Делится на две части левая и правая
 */
class ToolBarPanel extends Widget
{
    const ORIENTATION_LEFT = 'left';
    const ORIENTATION_RIGHT = 'right';

    /**
     *  Html код кнопок.
     *  Если значение массива строка. То код кнопка будет обернута <div class="btn-group">...</div>
     *  Если значение массива - массив то в блок <div class="btn-group">...</div> будет обернуты все кнопки из этого массива.
     *  Пример:
     *  [
     *      '<button type="submit" class="btn btn-primary">Сохранить</button>',
     *      '<button class="btn btn-primary">Отмена</button>',
     *      [
     *          <button class="btn btn-primary">Удалить</button>,
     *          <button class="btn btn-primary">Экспорт</button>
     *      ]
     *  ]
     *
     * @var array
     */
    public $buttons = [];

    /**
     * HTML опции контейнера div
     * @var array
     */
    public $options = ['class' => 'btn-toolbar', 'role' => 'toolbar'];

    /**
     * Ориентация панели.
     * Может быть
     * self::ORIENTATION_LEFT - левая;
     * self::ORIENTATION_RIGHT - правая;
     * @var array
     */
    public $orientation = self::ORIENTATION_LEFT;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $cssClass = ($this->orientation == self::ORIENTATION_LEFT) ? 'pull-left' : 'pull-right';
        Html::addCssClass($this->options, $cssClass);
    }

    public function run()
    {
        $panel = '';
        foreach ($this->buttons as $button) {
            if (empty($button)) continue;
            $panel .= Html::tag('div', (!is_array($button)) ? $button : implode('', $button), ['class' => 'btn-group']);
        }
        return Html::tag('div', $panel, $this->options);
    }
}