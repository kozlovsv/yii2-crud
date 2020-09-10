<?php


namespace kozlovsv\crud\assets;

use yii\web\AssetBundle;
use yii\web\View;


class FontAwesomeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $js = [
        'https://use.fontawesome.com/releases/v5.14.0/js/all.js'
    ];

    /**
     * @inheritdoc
     */
    public $jsOptions = [
        'position' => View::POS_HEAD,
        'defer' => true,
        'crossorigin' => 'anonymous',
        'integrity' => "sha384-3Nqiqht3ZZEO8FKj7GR1upiI385J92VwWNLj+FqHxtLYxd9l+WYpeqSOrLh0T12c"
    ];
}