<?php

namespace kozlovsv\crud\components;

use Yii;
use yii\base\Action;
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
    public $allow_not_auth_controllers = [];

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

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        if (!empty($this->allow_not_auth_controllers)) {
            $controllerId = Yii::$app->controller->id ?? '';
            if (in_array($controllerId, $this->allow_not_auth_controllers)) return true;
        }
        return parent::beforeAction($action);
    }
}