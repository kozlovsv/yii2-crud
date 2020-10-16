<?php

namespace kozlovsv\crud\assets;

use yii\web\AssetBundle;

class ButtonTopAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => YII_DEBUG];

    public $css = [
        'button-top.css',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/button-top';
        parent::init();
    }
}
