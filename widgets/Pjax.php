<?php

namespace kozlovsv\crud\widgets;


use yii\helpers\Json;
use yii\widgets\PjaxAsset;

class Pjax extends \yii\widgets\Pjax
{
    /**
     * При использовании PJAX  в модальных окнах возникла проблема.
     * Если вызвать окно. Оно загрузится методом GET и выведется на экран.
     * При выводе выполнится строчка JS jQuery(document).on($submitEvent ... тоесть на событие submit будет навенан слушатель.
     * Теперь закрываем окно (hide) и заново вызываем. Опять методом GET будет получен ког и выполнен повторно. Тоесть событие
     * jQuery(document).on($submitEvent будет выполнено второй раз. И так далее ...
     * Тоесть при каждом закрытиии и открытии окна, после submit выполнялись куча слушателей повешанных на это событие. Каждяы слушатель в свою очередь делал сабмит.
     * В итоге на сервер приходило куча одинаковых запросов.
     * Если это запрос на добавление записи, то в итоге в базу попадало несколько одинаковых записей а не одна.
     * Поэтому было решено переопределить этот метод. И перед включением jQuery(document).on($submitEvent добавить строчку выключения этого события
     * jQuery(document).off($submitEvent - и если оно было раньше включено, то оно выключится. Что не позволит
     * Переопределяем функцию регистрации скриптов.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;
        if (!isset($this->clientOptions['container'])) {
            $this->clientOptions['container'] = "#$id";
        }
        $options = Json::htmlEncode($this->clientOptions);
        $js = '';
        if ($this->linkSelector !== false) {
            $linkSelector = Json::htmlEncode($this->linkSelector !== null ? $this->linkSelector : '#' . $id . ' a');
            $js .= "jQuery(document).pjax($linkSelector, $options);";
        }
        if ($this->formSelector !== false) {
            $formSelector = Json::htmlEncode($this->formSelector !== null ? $this->formSelector : '#' . $id . ' form[data-pjax]');
            $submitEvent = Json::htmlEncode($this->submitEvent);
            $js .= "\njQuery(document).off($submitEvent, $formSelector);"; // !!!!!!!!!!! Добавленная строчка. Предварительно отключаем on('submit', ...  Если он уже раньше был включен
            $js .= "\njQuery(document).on($submitEvent, $formSelector, function (event) {jQuery.pjax.submit(event, $options);});";
        }
        $view = $this->getView();
        PjaxAsset::register($view);

        if ($js !== '') {
            $view->registerJs($js);
        }
    }
}