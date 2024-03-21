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

    /**
     * @inheritdoc
     */
    protected function additionalAccessRules():array
    {
        return[
            [
                'actions' => ['delete-all', 'truncate'],
                'allow' => ModelPermission::canDelete(ModelPermission::getPermissionCategory($this->getModelClassName())),
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
     * @return string
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


    public function actionTruncate()
    {
        try {
        $command = Log::getDb()->createCommand();
        $command->truncateTable(Log::tableName());
        $command->execute();
        Yii::$app->session->setFlash('success', 'Журнал логов успешно очищен.');
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            $message = 'При очистки журнала произошла ошибка.';
            Yii::$app->session->setFlash('error', $message);
        }
        return $this->goBackCrud();
    }
}