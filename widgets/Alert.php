<?php

namespace kozlovsv\crud\widgets;

use yii\bootstrap\Widget;
use yii\helpers\Html;

/**
 * Уведомление о выполнении
 *
 * @author Ilya Norkin <ilya@itender.kz>
 */
class Alert extends Widget
{
    /**
     * Скрывать автоматически
     * @var bool
     */
    public $autoClose = true;

    /**
     * Таймаут скрытия
     * @var int
     */
    public $closeTimeout = 5000;

    /**
     * Время закрытия
     * @var int
     */
    public $outTime = 400;

    /**
     * Эффект скрытия
     * @var string
     */
    public $outType = 'fadeOut';

    /**
     * Типы сообщений
     */
    public $alertTypes = [
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'success' => 'alert-success',
        'info' => 'alert-info',
        'warning' => 'alert-warning',
    ];

    public function init()
    {
        parent::init();
        $session = \Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';
        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    $this->options['class'] = "alert alert-dismissible " . $this->alertTypes[$type] . $appendCss;
                    $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;
                    $alert = Html::beginTag('div', $this->options);
                    $alert .= Html::button('×', ['class' => 'close', 'data-dismiss' => 'alert', 'aria-hidden' => true]);
                    $alert .= Html::tag('strong', $message);
                    $alert .= Html::endTag('div');
                    $this->registerAutoCloseJs("#{$this->options['id']}");

                    echo $alert;

                }
                $session->removeFlash($type);
            }
        }
    }

    protected function registerAutoCloseJs($selector)
    {
        if ($this->autoClose) {
            $view = $this->getView();
            $js = '$("' . $selector . '").delay(' . $this->closeTimeout . ').'. $this->outType .'('. $this->outTime .');';

            $view->registerJs($js, $view::POS_READY);
        }
    }

}
