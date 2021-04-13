<?php
namespace kozlovsv\crud\widgets;

use Yii;
use yii\widgets\Pjax as YiiPjax;


/**
 * Колонка действий над записью
 */
class Pjax extends YiiPjax
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!Yii::$app->request->isAjax) return;
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run() {
        if (!Yii::$app->request->isAjax) return '';
        return parent::run();
    }

}