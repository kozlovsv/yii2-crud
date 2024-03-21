<?php

namespace kozlovsv\crud\widgets;

use yii\widgets\MaskedInput;

/**
 * Class NumericInput
 * @package frontend\widgets
 */
class NumericInput extends MaskedInput
{
    public $clientOptions = [
        'removeMaskOnSubmit' => true,
        'alias' =>  'integer',
        'groupSeparator' => ' ',
        'autoGroup' => true,
    ];

    /**
     * @inheritdoc
     * @return void
     */
    public function run()
    {
        parent::run();
    }
}