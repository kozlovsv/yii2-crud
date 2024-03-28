<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use kozlovsv\crud\classes\BackRedirecter;
use kozlovsv\crud\classes\IBackRedirecrer;
use kozlovsv\crud\helpers\FindOneModelHelper;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
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
     * Класс для редиректа назад после успешного действия
     * @var string|array|IBackRedirecrer
     */
    public $successBackRedirecter = [
        'class' => BackRedirecter::class,
        'backUrl' => 'index',
    ];

    /**
     * Класс для редиректа назад в случае ошибки
     * @var string|array|IBackRedirecrer
     */
    public $errorBackRedirecter = [
        'class' => BackRedirecter::class,
        'backUrl' => 'index',
    ];

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
     * Сообщение при ошибки при отработке функции Action::run
     * @var string
     */
    public string $successMessage = '';

    /**
     * The hook function to be executed after finding a model.
     *
     * This hook function is called after finding a model in the code. It can be used to perform additional logic or manipulate the model data before it is returned.
     *
     * @var callable|null
     *
     * @see BaseCrudAction::findModel()
     */
    public $afterFindModelHook = null;

    /**
     * The hook that is called after a model was created.
     *
     * This hook allows custom code to be executed after a model has been created.
     *
     * @var callable|null
     *
     * @see BaseCrudAction::createModel()
     */
    public $afterCreateModelHook = null;

    /**
     * Обязательно проверять разрешение на доступ к модели.
     * @var bool
     */
    public bool $modelPermissionRequired = true;

    public function init()
    {
        if (empty($this->modelClassName))
            throw new InvalidConfigException('The "modelClassName" config is required.');
        if (!($this->successBackRedirecter instanceof IBackRedirecrer)) $this->successBackRedirecter = Yii::createObject($this->successBackRedirecter, ['controller' => $this->controller]);
        if (!($this->errorBackRedirecter instanceof IBackRedirecrer)) $this->errorBackRedirecter = Yii::createObject($this->errorBackRedirecter, ['controller' => $this->controller]);
        parent::init();
    }

    /**
     * @return Response
     */
    protected function goBackSuccess($id = null)
    {
        return $this->successBackRedirecter->back($id);
    }

    /**
     * @return Response
     */
    protected function goBackError($id = null)
    {
        return $this->errorBackRedirecter->back($id);
    }

    /**
     * @return void
     */
    protected function setFlash($key, $message)
    {
        if ($message) Yii::$app->session->setFlash($key, $message);
    }

    /**
     * @return void
     */
    protected function setFlashError($message)
    {
        $this->setFlash('error', $message);
    }

    /**
     * @return void
     */
    protected function setFlashSuccess($message)
    {
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
            return $this->doAction($model, $id);
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
     * Executes the main action.
     * This method is called by the Run method.
     *
     * @param Model $model The model associated with the action.
     * @param mixed $id The ID of the model. Defaults to null.
     * @return Response|string The response or the rendered string.
     * @throws ForbiddenHttpException if the user does not have the required permission.
     */
    abstract protected function doAction($model, $id);

    /**
     * @param int $id
     * @return Model
     */
    protected function findModel($id)
    {
        $model = FindOneModelHelper::findOneAndCheckAccess($id, $this->modelClassName, $this->permissionMethod, $this->modelPermissionRequired);
        $this->model = $model;

        if ($this->afterFindModelHook && is_callable($this->afterFindModelHook)) {
            call_user_func($this->afterFindModelHook, $model);
        }

        return $model;
    }

    /**
     * @return Model
     */
    protected function createModel()
    {
        /** @var Model $model */
        $model = new $this->modelClassName();
        $this->model = $model;

        if ($this->afterCreateModelHook && is_callable($this->afterCreateModelHook)) {
            call_user_func($this->afterCreateModelHook, $model);
        }

        return $model;
    }
}