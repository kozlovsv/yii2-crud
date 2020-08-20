<?php

namespace kozlovsv\crud\assets;

use yii\web\AssetBundle;

/**
 * StickrAsset
 */
class StickrAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $css = [
        'stickr.css',
    ];
    public $js = [
        'stickr.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/stickr';
        parent::init();
    }
}