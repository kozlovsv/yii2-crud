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

    /**
     * To show panel container om empty left and right panels
     * @var bool
     */
    public $showOnEmpty = true;

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

        return (!empty($content) || $this->showOnEmpty) ? Html::tag('div', $content, $this->options) : '';
    }
}