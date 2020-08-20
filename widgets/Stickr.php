<?php
namespace kozlovsv\crud\widgets;

use kozlovsv\crud\assets\StickrAsset;
use yii\bootstrap\Widget;
use yii\helpers\Json;

/**
 * Class Stickr
 * @package frontend\widgets
 */
class Stickr extends Widget
{
    /**
     * @var int
     */
    public $time; // количество мс, которое отображается сообщение

    /**
     * @var string
     */
    public $speed; // скорость анимации

    /**
     * @var string
     */
    public $note; // текст сообщения

    /**
     * @var string
     */
    public $className; // класс, добавляемый к сообщению

    /**
     * @var bool
     */
    public $sticked; // отключить автоматическое скрытие

    /**
     * @return void
     */
    public function run()
    {
        $this->registerJs();
    }

    /**
     * @return string
     */
    protected function getOptions()
    {
        $options = [];

        if (!empty($this->time)) $options['time'] = $this->time;
        if (!empty($this->speed)) $options['speed'] = $this->speed;
        if (!empty($this->note)) $options['note'] = $this->note;
        if (!empty($this->className)) $options['className'] = $this->className;
        if (!empty($this->sticked)) $options['sticked'] = $this->sticked;

        return Json::encode($options);
    }

    /**
     * Клиентские скрипты
     */
    protected function registerJs()
    {
        $view = $this->view;
        StickrAsset::register($view);
        $view->registerJs("$.stickr(" . $this->getOptions() . ");", $view::POS_READY);
    }
}