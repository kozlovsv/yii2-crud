<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * Базовый класс Action для выполнения операций в транзакции
 */
abstract class BaseCrudTransactionAction extends BaseCrudAction
{

    /**
     * Сообщение при ошибки при отработке функции Action::run
     * @var string
     */
    public string $successMessage = '';

    /**
     * Specific action which should be implemented in derived classes
     * @param $model
     * @return Response
     */
    protected function doAction($model) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->doActionModel($model)) {
                $transaction->commit();
                $this->setFlashSuccess($this->successMessage) ;
            } else {
                $transaction->rollBack();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $this->goBackSuccess($model->id);
    }

    /**
     * @param ActiveRecord $model
     * @return bool
     */
    protected abstract function doActionModel($model): bool;
}