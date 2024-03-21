<?php
namespace kozlovsv\crud\classes;

use yii\base\Event;
use yii\db\BaseActiveRecord;

class EventWithModel extends Event {
    public BaseActiveRecord $model;
}