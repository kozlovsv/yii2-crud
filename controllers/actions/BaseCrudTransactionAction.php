<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\Response;

/**
 * Базовый класс Action для выполнения операций в транзакции
 */
abstract class BaseCrudTransactionAction extends BaseCrudAction
{
    /**
     * Indicates whether to use the model ID to back success.
     *
     * @var bool $useModelIdToBackSuccess Flag to determine if the model ID should be used for back success.
     */
    public $useModelIdToBackSuccess = true;

    /**
     * Specific action which should be implemented in derived classes
     * @param ActiveRecord|Model $model
     * @param mixed $id
     * @return Response
     */
    protected function doAction($model, $id) {
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
        return $this->goBackSuccess($this->useModelIdToBackSuccess ? $model->getPrimaryKey() : $id);
    }

    /**
     * @param Model $model
     * @return bool
     */
    protected abstract function doActionModel($model): bool;
}