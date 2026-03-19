<?php

namespace kozlovsv\crud\classes;

use yii\base\Model;
use yii\base\ModelEvent as Yii2ModelEvent;

class ModelEvent extends Yii2ModelEvent
{
    public Model $model;
}