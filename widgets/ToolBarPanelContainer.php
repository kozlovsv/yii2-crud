<?php

namespace kozlovsv\crud\widgets;

use yii\bootstrap\Html;
use yii\bootstrap\Widget;


/**
 * Панель инструментов. Делится на две части левая и правая
 */
class ToolBarPanelContainer extends Widget
{
    /**
     * HTML container options
     * @var array
     */
    public $options = [];
    /**
     * Left buttons array
     * @see \kozlovsv\crud\widgets\ToolBarPanel::buttons
     * @var array
     */
    public $buttonsLeft = [];
    /**
     * Right buttons array
     * @see \kozlovsv\crud\widgets\ToolBarPanel::buttons
     * @var array
     */
    public $buttonsRight = [];

    public function run()
    {
        $content = ToolBarPanel::widget([
            'buttons' => $this->buttonsLeft,
            'orientation' => ToolBarPanel::ORIENTATION_LEFT,
        ]);

        $content .= ToolBarPanel::widget([
            'buttons' => $this->buttonsRight,
            'orientation' => ToolBarPanel::ORIENTATION_RIGHT,
        ]);

        return Html::tag('div', $content, $this->options);
    }
}