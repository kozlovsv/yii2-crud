<?php

namespace kozlovsv\crud\widgets;


use kozlovsv\crud\assets\ButtonTopAsset;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;

class ButtonTop extends Widget
{

    public $scrollStart = 100;
    public $bottom = 10;
    public $right = 10;
    public $width = 60;
    public $height = 60;
    public $zindex = 1040;


    public function run()
    {
        $style = "bottom: {$this->bottom}px; right: {$this->right}px; width: {$this->width}px; height: {$this->height}px; z-index: {$this->zindex}; background-size: {$this->width}px {$this->height}px;";
        $this->registerJs();
        return Html::a('', '#top', ['id' => 'button-top', 'class' => 'anchor', 'style' => $style]);

    }

    /**
     * Клиентские скрипты
     */
    protected function registerJs()
    {
        $view = $this->view;
        ButtonTopAsset::register($this->view);
        $js =
            "
            if ($(window).scrollTop() > {$this->scrollStart}) $('#button-top').show(); 
            $(window).scroll(function() {
            if ($(window).scrollTop() > {$this->scrollStart}) {
                $('#button-top').show();
            } else {
                $('#button-top').hide();
            }
        });";
        $view->registerJs($js, $view::POS_READY);
    }
}