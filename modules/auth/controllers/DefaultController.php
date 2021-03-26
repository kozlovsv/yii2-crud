<?php

namespace kozlovsv\crud\modules\auth\controllers;

use Yii;
use yii\helpers\Inflector;
use yii\web\Controller;
use kozlovsv\crud\modules\auth\models\form\RbacRoleForm;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\ArrayHelper;

/**
 * Управлене доступом
 */
class DefaultController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'permissions', 'add', 'delete', 'create'],
                        'allow' => true,
                        'roles' => ['auth.manage'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $roles = ArrayHelper::map($auth->getRoles(), 'name', 'description');
        asort($roles, SORT_STRING);
        /** @noinspection MissedViewInspection */
        return $this->render('index', compact('roles'));
    }

    /**
     * Получить списки ролей и прав
     * @param $role
     * @return array
     */
    public function actionPermissions($role)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissionsByRole($role);
        $permissionList = $auth->getPermissions();
        foreach ($permissionList as $key => $permission) {
            if (isset($permissions[$key])) {
                unset($permissionList[$key]);
            }
        }
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        $permissionList = ArrayHelper::map($permissionList, 'name', 'description');
        asort($permissions, SORT_STRING);
        asort($permissionList, SORT_STRING);
        return [
            'permissions' => $permissions,
            'permissionList' => $permissionList
        ];
    }

    /**
     * Выдать права
     * @param $role
     * @return bool
     */
    public function actionAdd($role)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;
        if ($post = Yii::$app->request->getBodyParam('permissions')) {
            $roleItem = $auth->getRole($role);
            foreach ($post as $permissionName) {
                $permissionItem = $auth->getPermission($permissionName);
                $auth->addChild($roleItem, $permissionItem);
            }
        }
        Yii::$app->cache->flush();
        return true;
    }

    /**
     * Забрать права
     * @param $role
     * @return bool
     */
    public function actionDelete($role)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;
        if ($post = Yii::$app->request->getBodyParam('permissions')) {
            $roleItem = $auth->getRole($role);
            foreach ($post as $permissionName) {
                $permissionItem = $auth->getPermission($permissionName);
                $auth->removeChild($roleItem, $permissionItem);
            }
        }
        Yii::$app->cache->flush();
        return true;
    }

    /**
     * Создание роли
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RbacRoleForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $auth = Yii::$app->authManager;
            $name = Inflector::slug($model->description);
            $role = $auth->createRole($name);
            $role->description = $model->description;
            if ($auth->add($role)) {
                return $this->redirect('index');
            }
        }

        /** @noinspection MissedViewInspection */
        return $this->renderAjax('create', [
            'model' => $model
        ]);
    }

}