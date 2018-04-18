<?php

namespace kozlovsv\crud\widgets;


use kozlovsv\datepicker\DateTimePicker;

class DatePicker extends DateTimePicker
{
    public function init()
    {
        parent::init();
        $this->clientOptions = array_merge(
            [
                'language' => 'ru',
                'format' => 'DD.MM.YYYY',
                'useCurrent' => false,
                'pickTime' => false,
            ],
            $this->clientOptions
        );
    }
}