<?php

namespace kozlovsv\crud\modules\log\controllers;

use Exception;
use kozlovsv\crud\controllers\CrudController;
use kozlovsv\crud\helpers\ModelPermission;
use kozlovsv\crud\modules\log\models\Log;
use kozlovsv\crud\modules\log\models\LogSearch;
use Yii;

/**
 * Управление логом
 */
class DefaultController extends CrudController
{

    public function init()
    {
        parent::init();
        $permissionCategory = $this->getPermissionCategory();
        $this->accessRules = [
            [
                'actions' => ['delete-all'],
                'allow' => ModelPermission::canDelete($permissionCategory),
            ],
        ];
    }


    /**
     * Возвращает модель для поиска
     * @return LogSearch
     */
    public function getSearchModel()
    {
        return new LogSearch();
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected function getModelClassName()
    {
        return 'kozlovsv\crud\modules\log\models\Log';
    }

    /**
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDeleteAll()
    {
        try {
            $ids = Yii::$app->request->post('ids');
            $count = Log::deleteAll([
                'id' => $ids,
            ]);
            if ($count) {
                Yii::$app->session->setFlash('success', 'Записи успешно удалены');
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $message = 'При удалении записей произошла ошибка.';
            Yii::$app->session->setFlash('error', $message);
        }
        return '';
    }
}