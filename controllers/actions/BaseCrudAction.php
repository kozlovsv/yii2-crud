<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use kozlovsv\crud\helpers\FindOneModelHelper;
use kozlovsv\crud\helpers\ReturnUrl;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Базовый абстрактный класс Action для
 */
abstract class BaseCrudAction extends Action
{
    /**
     * @var Model | null
     */
    protected $model = null;

    /**
     * URL для возврата назад после действия. Может быть в формате для Url::to(). Может передаваться как анонимная функция.
     * Сигнатура функции function($model) : Responce
     * @var array|string|callable
     */
    public $backUrl = 'index';

    /**
     * URL для возврата назад если в действии произошла ошибка.
     * Может быть в формате для Url::to(). Может передаваться как анонимная функция.
     * Сигнатура функции function($model) : Responce
     * @var array|string
     */
    public $errorBackUrl = 'index';

    /**
     * Если = true, то к URL $backUrl и $errorBackUrl будет доабвяляться параметр ID, для возврата назад
     * @var bool
     */
    public $addIdParemeterInBackUrl = false;

    /**
     * Имя базового класса модели. Используется для поика и создания модели.
     * @var string
     */
    public string $modelClassName = '';

    /**
     * @see BaseModelPermission::checkAccess()
     * @var string
     */
    public $permissionMethod = '';

    /**
     * Функция проверки соблюдения условий, для запуска Action. Если условия не соблюдены, то Action не выполняется.
     * Отличие от checkPermission в том что не выводится сообщение об ошибке. Просто идет редирект назад.
     * Сигнатура функции function ($model): bool
     * Внутри функии можно путем Yii::$app->session->setFlash() указывать сообшения, какие условия не выполнены.
     * @var callable | null
     */
    public $onCheckConditionAction = null;

    /**
     * Сообщение при ошибки при отработке функции Action::run
     * @var string
     */
    public string $errorMessage = '';

    /**
     * Обязательно проверять разрешение на доступ к модели.
     * @var bool
     */
    public bool $modelPermissionRequired = true;

    public function init()
    {
        if (empty($this->modelClassName))
            throw new InvalidConfigException('The "modelClassName" config is required.');
        parent::init();
    }

    /**
     * @return Response
     */
    protected function goBack($backUrl, $addIdParemeterInBackUrl, $id = null)
    {
        if (is_callable($backUrl)) {
            return call_user_func($backUrl, $this->model);
        }
        $url = $addIdParemeterInBackUrl? ReturnUrl::addIdToUrl($backUrl, $id) : $backUrl;
        return ReturnUrl::goBack($this->controller, $url);
    }

    /**
     * @return Response
     */
    protected function goBackSuccess($id = null)
    {
        return $this->goBack($this->backUrl, $this->addIdParemeterInBackUrl, $id);
    }

    /**
     * @return Response
     */
    protected function goBackError($id = null)
    {
        return $this->goBack($this->errorBackUrl, $this->addIdParemeterInBackUrl, $id);
    }

    /**
     * @return void
     */
    protected function setFlash($key, $message) {
        if ($message) Yii::$app->session->setFlash($key, $message);
    }

    /**
     * @return void
     */
    protected function setFlashError($message) {
        $this->setFlash('error', $message);
    }

    /**
     * @return void
     */
    protected function setFlashSuccess($message) {
        $this->setFlash('success', $message);
    }

    /**
     * Выполняет основное действие Action.
     * Так же внутри происходят проверки.
     *  - Разрешение доступа к операции permissionMethod, в сулчае если идет поиск модели, при создании модели проверка не происходит.
     *    Если действиене разрешено идет редирект по Url errorBackUrl, с выводом текста ошибки.
     *  - Так же идет проверка на соответствие условиям, если задана соответствующея анонимная функция. Если проверка не пройдена, то
     *    Action не выолняется, сразу идет редирект на errorBackUrl
     * Если все проверки пройдены идет выполнение функции действия doAction
     * @param $id
     * @return Response|string
     * @throws ForbiddenHttpException
     */
    public function Run($id = null)
    {
        try {
            $model = $id ? $this->findModel($id) : $this->createModel();
            if (!is_null($this->onCheckConditionAction) && !call_user_func($this->onCheckConditionAction, $model, $this)) return $this->goBackError($id);
            return $this->doAction($model);
        } catch (ForbiddenHttpException $e) {
            $this->setFlashError($e->getMessage());
            return $this->goBackError($id);
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            $this->setFlashError($this->errorMessage);
            return $this->goBackError($id);
        }
    }

    /**
     * Specific action which should be implemented in derived classes
     * @param $model
     * @return Response|string
     */
    abstract protected function doAction($model);

    /**
     * @param int $id
     * @return ActiveRecord
     */
    protected function findModel($id)
    {
        return FindOneModelHelper::findOneAndCheckAccess($id, $this->modelClassName, $this->permissionMethod, $this->modelPermissionRequired);
    }

    /**
     * @return ActiveRecord
     */
    protected function createModel()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClassName();
        $this->model = $model;
        return $model;
    }
}