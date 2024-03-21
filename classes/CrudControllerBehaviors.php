<?php

namespace kozlovsv\crud\classes;

use kozlovsv\crud\filters\RememberQueryParams;
use kozlovsv\crud\helpers\ModelPermission;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class CrudControllerBehaviors
{
    public static function config($permissionCategory, $accessRules)
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => array_merge([
                    [
                        'actions' => ['index', 'view'],
                        'allow' => ModelPermission::canView($permissionCategory),
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => ModelPermission::canCreate($permissionCategory),
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => ModelPermission::canUpdate($permissionCategory),
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => ModelPermission::canDelete($permissionCategory),
                    ],
                ], $accessRules),
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'rememberQueryParams' => [
                'class' => RememberQueryParams::class,
                'only' => ['index'],
            ],
        ];
    }

}