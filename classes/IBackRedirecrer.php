<?php
namespace kozlovsv\crud\classes;

use yii\base\Model;
use yii\web\Response;

interface IBackRedirecrer
{
    /**
     * @param int|string|null $id
     * @param Model|null $model
     * @return string|Response
     */
    public function back(string|int|null $id = null, ?Model $model = null): string|Response;
}