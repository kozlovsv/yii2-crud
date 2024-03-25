<?php
namespace kozlovsv\crud\classes;

use yii\web\Response;

interface IBackRedirecrer
{
    /**
     * @param int|string|null $id
     * @return string|Response
     */
    public function back($id = null);
}