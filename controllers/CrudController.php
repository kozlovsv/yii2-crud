<?php

namespace kozlovsv\crud\controllers;

use kozlovsv\crud\controllers\actions\ActionCrudCreate;
use kozlovsv\crud\controllers\actions\ActionCrudDelete;
use kozlovsv\crud\controllers\actions\ActionCrudIndex;
use kozlovsv\crud\controllers\actions\ActionCrudUpdate;
use kozlovsv\crud\controllers\actions\ActionCrudView;
use kozlovsv\crud\helpers\ModelPermission;
use yii\db\ActiveRecord;
use yii\web\Controller;

/**
 * Каркас контроллера CRUD
 * Class CrudController
 * @property string modelClassName
 * @package kozlovsv\crud\controllers
 */
abstract class CrudController extends Controller
{
    /**
     * @var array
     */
    protected array $actionIndexConfig = [];

    /**
     * @var array
     */
    protected array $actionViewConfig = [];

    /**
     * @var array
     */
    protected array $actionUpdateConfig = [];

    /**
     * @var array
     */
    protected array $actionCreateConfig = [];

    /**
     * @var array
     */
    protected array $actionDeleteConfig = [];


    protected function getActionIndexConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudIndex::class,
                'searchModel' => $this->getSearchModel(),
            ],
            $this->actionIndexConfig
        );
    }

    protected function getActionViewConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudView::class,
                'modelClassName' => $this->getModelClassName(),
            ],
            $this->actionViewConfig
        );
    }

    protected function getActionUpdateConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudUpdate::class,
                'modelClassName' => $this->getModelClassName(),
            ],
            $this->actionUpdateConfig
        );
    }

    protected function getActionCreateConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudCreate::class,
                'modelClassName' => $this->getModelClassName(),
            ],
            $this->actionCreateConfig
        );
    }

    protected function getActionDeleteConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudDelete::class,
                'modelClassName' => $this->getModelClassName(),
            ],
            $this->actionDeleteConfig
        );
    }

    public function actions()
    {
        return array_merge([
            'index' => $this->getActionIndexConfig(),
            'view' => $this->getActionViewConfig(),
            'update' => $this->getActionUpdateConfig(),
            'delete' => $this->getActionDeleteConfig(),
            'create' => $this->getActionCreateConfig(),
        ], $this->additionalActions());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return CrudControllerBehaviors::config($this->getPermissionCategory(), $this->additionalAccessRules());
    }

    protected function getPermissionCategory(){
        return ModelPermission::getPermissionCategory($this->getModelClassName());
    }

    /**
     * Дополнительные классы Action
     * @return array
     */
    protected function additionalActions(): array {
        return [];
    }

    /**
     * @return array
     */
    protected function additionalAccessRules(): array
    {
        return [];
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
    protected abstract function getSearchModel();
}