<?php

namespace kozlovsv\crud\components;

use Yii;
use yii\filters\AccessControl;

/**
 * Компонента проверки доступа в систему
 */
class RequestAccess extends AccessControl
{
    /**
     * @var array
     */
    public $allow_not_auth_actions = [];

    /**
     * @var array
     */
    public $rules = [
        [
            'allow' => true,
            'roles' => ['@'],
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->denyCallback = function () {
            Yii::$app->user->logout();
            return Yii::$app->response->redirect(Yii::$app->user->loginUrl);
        };
        $this->rules[] = [
            'allow' => true,
            'actions' => $this->allow_not_auth_actions,
        ];
        parent::init();
    }
}