<?php
namespace kozlovsv\crud\widgets;


use yii\helpers\ArrayHelper;

class Select2 extends \kartik\select2\Select2
{
    public function init()
    {
        parent::init();
        $this->pluginOptions = ArrayHelper::merge(
            ['allowClear' => true],
            $this->pluginOptions
        );
    }
}