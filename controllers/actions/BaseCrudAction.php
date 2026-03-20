<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use kozlovsv\crud\classes\IBackRedirecrer;
use kozlovsv\crud\helpers\CreateCrudObjectHelper;
use kozlovsv\crud\helpers\FindOneModelHelper;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Базовый абстрактный класс Action для
 */
abstract class BaseCrudAction extends Action
{
    /**
     * @var Model | null
     */
    public $model = null;

    /**
     * Класс для редиректа назад после успешного действия
     * @var string|array|IBackRedirecrer
     */
    public $successBackRedirecter = ['backUrl' => 'index'];

    /**
     * Класс для редиректа назад в случае ошибки
     * @var string|array|IBackRedirecrer
     */
    public $errorBackRedirecter = ['backUrl' => 'index'];

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
     * The hook function to be executed after finding or created a model.
     *
     * This hook function is called after finding a model in the code. It can be used to perform additional logic or manipulate the model data before it is returned.
     *
     * @var callable|null
     *
     * @see BaseCrudAction::getModel()
     */
    public $afterGetModelHook = null;

    /**
     * Обязательно проверять разрешение на доступ к модели.
     * @var bool
     */
    public bool $modelPermissionRequired = true;

    public function init()
    {
        if (empty($this->modelClassName) && empty($this->model))
            throw new InvalidConfigException('The "modelClassName" or "model" config is required.');
        $this->successBackRedirecter = CreateCrudObjectHelper::createRedirecter($this->controller, $this->successBackRedirecter);
        $this->errorBackRedirecter = CreateCrudObjectHelper::createRedirecter($this->controller, $this->errorBackRedirecter);
        parent::init();
    }

    /**
     * @return Response
     */
    protected function goBackSuccess($id = null, $model = null)
    {
        return $this->successBackRedirecter->back($id, $model);
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
            $model = $this->getModel($id);
            if (!is_null($this->onCheckConditionAction) && !call_user_func($this->onCheckConditionAction, $model, $this)) return $this->goBackError($id);
            return $this->doAction($model, $id);
        } catch (ForbiddenHttpException|NotFoundHttpException $e) {
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
        return FindOneModelHelper::findOneAndCheckAccess($id, $this->modelClassName, $this->permissionMethod, $this->modelPermissionRequired);
    }

    /**
     * @return Model
     */
    protected function createModel()
    {
        throw new NotSupportedException('Метод "createModel" должен быть переопределен в классе предке');
    }

    protected function getModel(int|null $id)
    {
        if (empty($this->model)) {
            $this->model = $id ? $this->findModel($id) : $this->createModel();
        }
        if ($this->afterGetModelHook) {
            call_user_func($this->afterGetModelHook, $this->model, $this);
        }
        return $this->model;
    }
}