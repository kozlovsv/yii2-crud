<?php

namespace kozlovsv\crud\assets;

use yii\web\AssetBundle;

/**
 * StickrAsset
 */
class StickrAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $sourcePath = '/assets/stickr';
    public $css = [
        'stickr.css',
    ];
    public $js = [
        'stickr.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}