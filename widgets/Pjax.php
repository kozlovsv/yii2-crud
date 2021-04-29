<?php

namespace kozlovsv\crud\widgets;

use Yii;
use yii\widgets\Pjax as YiiPjax;


/**
 * Колонка действий над записью
 */
class Pjax extends YiiPjax
{
    public $onlyForDialog = true;

    /**
     * @var int pjax timeout setting (in milliseconds). This timeout is used when making AJAX requests.
     * Use a bigger number if your server is slow. If the server does not respond within the timeout,
     * a full page load will be triggered.
     */
    public $timeout = 5000;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!Yii::$app->request->isAjax && $this->onlyForDialog) return;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (!Yii::$app->request->isAjax && $this->onlyForDialog) return '';
        //Значение не работают в самом виджете. Помогает только вот такая вот установка.
        //В PJAX Jquery стоит по умолчанию таймаут 650. Если его превысить то просиходит полная перезагрузка страницы.
        //Установка значения timeout самого виджета не дает результата. Тоже самое и со сзначением scrollTo оно не работает если установить его в виджете.
        $scrolTo = $this->scrollTo === false ? 'false' : $this->scrollTo;
        $js = "
            $.pjax.defaults.timeout = {$this->timeout};
            $.pjax.defaults.scrollTo = {$scrolTo};
        ";
        $this->view->registerJs($js, $this->view::POS_END);
        return parent::run();
    }
}