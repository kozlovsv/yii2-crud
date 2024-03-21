<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\Response;


abstract class ActionCrudUpdate extends ActionCrudOperation
{
    /**
     * @var string
     */
    public string $viewName = 'update';

    /**
     * @var string
     */
    public string $successMessage = 'Данные успешно сохранены';

    /**
     * @var string
     */
    public string $errorMessage = 'При сохранении записи произошла ошибка. Обратитесь в службу поддержки.';


    /**
     * @param $id
     * @return Response|string
     */
    public function run($id)
    {
        try {
            $model = $this->findModel($id);
            return $this->doRun($model);
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goBackAfterError();
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            $this->flashError();
            return $this->goBackAfterError();
        }
    }
}