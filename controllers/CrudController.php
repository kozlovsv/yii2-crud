<?php

namespace kozlovsv\crud\controllers;

use kozlovsv\crud\classes\CrudControllerBehaviors;
use kozlovsv\crud\controllers\actions\ActionCrudCreate;
use kozlovsv\crud\controllers\actions\ActionCrudDelete;
use kozlovsv\crud\controllers\actions\ActionCrudIndex;
use kozlovsv\crud\controllers\actions\ActionCrudUpdate;
use kozlovsv\crud\controllers\actions\ActionCrudView;
use kozlovsv\crud\helpers\ReturnUrl;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\Response;


/**
 * Каркас контроллера CRUD
 * Class CrudController
 * @property string modelClassName
 * @package kozlovsv\crud\controllers
 */
abstract class CrudController extends Controller
{
    /**
     * Дополнительные настройки доступа
     * @var array
     */
    protected array $accessRules = [];

    /**
     * Контроллер куда возвращаться по умолчанию.
     * @var string
     */
    protected string $defaultBackUrl = 'index';

    /**
     * Добавлять Flash сообщения после добавления, редактирования, удаления записи.
     * @var bool
     */
    protected bool $addFlashMessages = true;

    /**
     * @var array
     */
    protected array $defaultActionIndexConfig = [];

    /**
     * @var array
     */
    protected array $defaultActionViewConfig = [];

    /**
     * @var array
     */
    protected array $defaultActionUpdateConfig = [];

    /**
     * @var array
     */
    protected array $defaultActionCreateConfig = [];

    /**
     * @var array
     */
    protected array $defaultActionDeleteConfig = [];

    /**
     * @var string
     */
    protected string $defaultActionIndexClassName = ActionCrudIndex::class;

    /**
     * @var string
     */
    protected string $defaultActionViewClassName = ActionCrudView::class;

    /**
     * @var string
     */
    protected string $defaultActionUpdateClassName = ActionCrudUpdate::class;

    /**
     * @var string
     */
    protected string $defaultActionCreateClassName = ActionCrudCreate::class;

    /**
     * @var string
     */
    protected string $defaultActionDeleteClassName = ActionCrudDelete::class;


    protected function getActionIndexConfig(): array
    {
        return array_merge(
            [
                'class' => $this->defaultActionIndexClassName,
                'searchModel' => $this->getSearchModel(),
            ],
            $this->defaultActionIndexConfig
        );
    }

    protected function getActionViewConfig(): array
    {
        return array_merge(
            [
                'class' => $this->defaultActionViewClassName,
                'modelClassName' => $this->getModelClassName(),
                'backUrl' => $this->defaultBackUrl,
            ],
            $this->defaultActionViewConfig
        );
    }

    protected function getActionUpdateConfig(): array
    {
        return array_merge(
            [
                'class' => $this->defaultActionUpdateClassName,
                'modelClassName' => $this->getModelClassName(),
                'backUrl' => $this->defaultBackUrl,
                'addFlashMessages' => $this->addFlashMessages,
            ],
            $this->defaultActionUpdateConfig
        );
    }

    protected function getActionCreateConfig(): array
    {
        return array_merge(
            [
                'class' => $this->defaultActionCreateClassName,
                'modelClassName' => $this->getModelClassName(),
                'backUrl' => $this->defaultBackUrl,
                'addFlashMessages' => $this->addFlashMessages,
                'on afterCreateModel' => [$this, 'afterCreate']
            ],
            $this->defaultActionCreateConfig
        );
    }

    protected function getActionDeleteConfig(): array
    {
        return array_merge(
            [
                'class' => $this->defaultActionDeleteClassName,
                'modelClassName' => $this->getModelClassName(),
                'backUrl' => $this->defaultBackUrl,
                'addFlashMessages' => $this->addFlashMessages,
            ],
            $this->defaultActionDeleteConfig
        );
    }


    public function actions()
    {
        return [
            'index' => $this->getActionIndexConfig(),
            'view' => $this->getActionViewConfig(),
            'update' => $this->getActionUpdateConfig(),
            'delete' => $this->getActionDeleteConfig(),
            'create' => $this->getActionCreateConfig(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return CrudControllerBehaviors::config($this->getModelClassName(), $this->accessRules);
    }

    /**
     * Возвращает полное имя класса модели с пространством имен
     * @return string
     */
    protected abstract function getModelClassName();

    /**
     * Возвращает модель для поиска
     * @return ActiveRecord
     */
    public abstract function getSearchModel();

    /**
     * @return Response
     */
    public function goBackCrud()
    {
        return ReturnUrl::goBack($this, $this->defaultBackUrl);
    }

    public function afterCreate($event) {
        //empty
    }
}