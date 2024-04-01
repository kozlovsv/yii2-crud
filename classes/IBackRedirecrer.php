<?php
namespace kozlovsv\crud\classes;

use yii\base\Model;

interface IBackRedirecrer
{
    /**
     * @param int|string|null $id
     * @param Model|null $model
     * @return mixed
     */
    public function back(string|int|null $id = null, ?Model $model = null);
}