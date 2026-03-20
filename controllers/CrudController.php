<?php

namespace kozlovsv\crud\controllers;

use kozlovsv\crud\classes\BackRedirecter;
use kozlovsv\crud\controllers\actions\ActionCrudCreate;
use kozlovsv\crud\controllers\actions\ActionCrudDelete;
use kozlovsv\crud\controllers\actions\ActionCrudIndex;
use kozlovsv\crud\controllers\actions\ActionCrudUpdate;
use kozlovsv\crud\controllers\actions\ActionCrudView;
use kozlovsv\crud\helpers\CreateCrudObjectHelper;
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

    /**
     * @var bool
     */
    protected bool $modelPermissionRequired = true;

    private BackRedirecter $_backRedirecter;

    public function init()
    {
        parent::init();
        $this->_backRedirecter = CreateCrudObjectHelper::createRedirecter($this);
    }

    protected function getActionIndexConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudIndex::class,
                'searchModel' => $this->getSearchModel(),
            ],
            $this->actionIndexConfig,
            $this->getCustomActionIndexConfig(),
        );
    }

    protected function getActionViewConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudView::class,
                'modelClassName' => $this->getViewModelClassName(),
                'modelPermissionRequired' => $this->modelPermissionRequired,
            ],
            $this->actionViewConfig,
            $this->getCustomActionViewConfig(),
        );
    }

    protected function getActionUpdateConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudUpdate::class,
                'modelClassName' => $this->getUpdateModelClassName(),
                'modelPermissionRequired' => $this->modelPermissionRequired,
            ],
            $this->actionUpdateConfig,
            $this->getCustomActionUpdateConfig(),
        );
    }

    protected function getActionCreateConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudCreate::class,
                'modelClassName' => $this->getCreateModelClassName(),
            ],
            $this->actionCreateConfig,
            $this->getCustomActionCreateConfig(),
        );
    }

    protected function getActionDeleteConfig(): array
    {
        return array_merge(
            [
                'class' => ActionCrudDelete::class,
                'modelClassName' => $this->getModelClassName(),
                'modelPermissionRequired' => $this->modelPermissionRequired,
            ],
            $this->actionDeleteConfig,
            $this->getCustomActionDeleteConfig(),
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

    protected function getCreateModelClassName() {
        return $this->getModelClassName();
    }

    protected function getViewModelClassName() {
        return $this->getModelClassName();
    }

    protected function getUpdateModelClassName() {
        return $this->getModelClassName();
    }

    protected function getDeleteModelClassName() {
        return $this->getModelClassName();
    }

    /**
     * Возвращает модель для поиска
     * @return ActiveRecord
     */
    protected abstract function getSearchModel();

    protected function goBackCrud()
    {
        return $this->_backRedirecter->back();
    }

    /**
     * Дополнительные настройки для Action, отличаются от параметров переменной actionDeleteConfig,тем
     * что в функции можно задавать анонимные фунции хуки, а в перемерах переменной PHP этого не дает
     * @return array
     */
    protected function getCustomActionDeleteConfig(): array
    {
        return [];
    }

    protected function getCustomActionIndexConfig(): array
    {
        return [];
    }

    protected function getCustomActionViewConfig(): array
    {
        return [];
    }

    protected function getCustomActionCreateConfig(): array
    {
        return [];
    }

    protected function getCustomActionUpdateConfig(): array
    {
        return [];
    }


}