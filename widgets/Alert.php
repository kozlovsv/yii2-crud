<?php

namespace kozlovsv\crud\widgets;


use Yii;
use yii\bootstrap\Widget;

/**
 *
 */
class Alert extends Widget
{
    /**
     * @var array
     */
    public $alertTypes = [
        'error'  => 'stickr-danger',
        'danger'  => 'stickr-danger',
        'success' => 'stickr-success',
    ];

    /**
     * @inheritdoc
     */

    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    Stickr::widget(['note' => $message, 'className' => $this->alertTypes[$type]]);
                }
                $session->removeFlash($type);
            }
        }
    }
}