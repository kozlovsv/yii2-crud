<?php

namespace kozlovsv\crud\controllers;

use Exception;
use kozlovsv\crud\filters\RememberQueryParams;
use kozlovsv\crud\helpers\ModelPermission;
use kozlovsv\crud\helpers\ReturnUrl;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * Каркас контроллера CRUD
 * Class CrudController
 * @property string modelClassName
 * @package kozlovsv\crud\controllers
 */
abstract class CrudController extends Controller
{
    const TYPE_ACTION_DELETE = 'delete';
    const TYPE_ACTION_VIEW = 'view';
    const TYPE_ACTION_CREATE = 'create';
    const TYPE_ACTION_UPDATE = 'update';

    /**
     * Дополнительные настройки доступа
     * @var array
     */
    protected $accessRules = [];

    /**
     * Контроллер куда возвращаться по умолчанию.
     * @var string
     */
    public $defaultBackUrl = 'index';
    /**
     * Имя View для просмотра
     * @var string
     */
    public $viewViewName = 'view';
    /**
     * Имя View для создяния
     * @var string
     */
    public $createViewName = 'create';
    /**
     * Имя View для обновления
     * @var string
     */
    public $updateViewName = 'update';
    /**
     * Имя View для index
     * @var string
     */
    public $indexViewName = 'index';

    /**
     * Загружать значения по умолчанию при создании модели
     * @var bool
     */
    public $loadDefaultValue = true;

    /**
     * Загружать значения переданные через Get параметры
     * @var bool
     */
    public $loadGetValue = false;

    /**
     * @var string
     */
    public $successCreateMessage = 'Данные успешно сохранены';

    /**
     * @var string
     */
    public $errorCreateMessage = 'При создании записи произошла ошибка. Обратитесь в службу поддержки.';

    /**
     * @var string
     */
    public $successDeleteMessage = 'Запись удалена';

    /**
     * @var string
     */
    public $errorDeleteMessage = 'Запись не может быть удалена, имеются связанные данные';

    /**
     * @var string
     */
    public $successUpdateMessage = 'Данные успешно сохранены';

    /**
     * @var string
     */
    public $errorUpdateMessage = 'При сохранении записи произошла ошибка. Обратитесь в службу поддержки.';

    /**
     * Обновляемая, удаляемая или добавленная модель.
     * @return ActiveRecord
     */
    protected $model;

    /**
     * Добавлять Flash сообщения после добавления, редактирования, удаления записи.
     * @var bool
     */
    public $addFlashMessages = true;

    /**
     * Произошла ошибка?
     * @var bool
     */
    public $isErrorInAction = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $permissionCategory = $this->getPermissionCategory();
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => array_merge([
                    [
                        'actions' => ['index', 'view'],
                        'allow' => ModelPermission::canView($permissionCategory),
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => ModelPermission::canCreate($permissionCategory),
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => ModelPermission::canUpdate($permissionCategory),
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => ModelPermission::canDelete($permissionCategory),
                    ],
                ], $this->accessRules),
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'rememberQueryParams' => [
                'class' => RememberQueryParams::class,
                'only' => ['index'],
            ],
        ];
    }

    public function actionCreate()
    {
        try {
            $model = $this->createModel();
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->save(false);
                    $this->afterCreate($model);
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', $this->successCreateMessage);
                return $this->goBackAfterCreate();
            }
            return $this->renderIfAjax($this->createViewName, compact('model'));
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            $this->isErrorInAction = true;

        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            if ($this->addFlashMessages) {
                $message = $this->errorCreateMessage;
                Yii::$app->session->setFlash('error', $message);
            }
            $this->isErrorInAction = true;
        }
        return $this->goBackAfterCreate();
    }

    /**
     * @param $id
     * @return Response
     * @throws Exception
     */
    public function actionDelete($id)
    {
        try {
            $model = $this->findModel($id);
            $this->afterFindModel($model, self::TYPE_ACTION_DELETE);
            if ($model->delete()) {
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', $this->successDeleteMessage);
            }
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            $this->isErrorInAction = true;
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            if ($this->addFlashMessages) {
                $message = $this->errorDeleteMessage;
                Yii::$app->session->setFlash('error', $message);
            }
            $this->isErrorInAction = true;
        }
        return $this->goBackAfterDelete();
    }

    public function actionUpdate($id)
    {
        try {
            $model = $this->findModel($id);
            $this->afterFindModel($model, self::TYPE_ACTION_UPDATE);
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {
                if ($this->addFlashMessages) Yii::$app->session->setFlash('success', $this->successUpdateMessage);
                return $this->goBackAfterUpdate();
            }
            return $this->renderIfAjax($this->updateViewName, compact('model'));
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            $this->isErrorInAction = true;
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            if ($this->addFlashMessages) {
                $message = $this->errorUpdateMessage;
                Yii::$app->session->setFlash('error', $message);
            }
            $this->isErrorInAction = true;
        }
        return $this->goBackAfterUpdate();
    }

    public function actionIndex()
    {
        $searchModel = $this->getSearchModel();
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->indexViewName, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        try {
            $model = $this->findModel($id);
            $this->afterFindModel($model, self::TYPE_ACTION_VIEW);
            return $this->renderIfAjax($this->viewViewName, compact('model'));
        } catch (ForbiddenHttpException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->goBackCrud();
        }
    }

    /**
     * @param $id
     * @param bool $noCache Брать модель не из кэша, а запрашивать заново
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function findModel($id, $noCache = false)
    {
        if (!empty($this->model) && !$noCache) return $this->model;
        $modelClass = $this->modelClassName;
        /** @noinspection PhpUndefinedMethodInspection */
        $model = $modelClass::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        $this->model = $model;
        return $model;
    }

    /**
     * @return ActiveRecord
     */
    public function createModel()
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClassName();
        if ($this->loadDefaultValue) $model->loadDefaultValues(true);
        if ($this->loadGetValue) $model->load(Yii::$app->request->get());
        $this->model = $model;
        return $model;
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

    /**
     * Возврат после добавления записи
     * @return Response
     */
    public function goBackAfterCreate()
    {
        return $this->goBackCrud();
    }

    /**
     * Возврат после обновления записи
     * @return Response
     */
    public function goBackAfterUpdate()
    {
        return $this->goBackCrud();
    }

    /**
     * Возврат после удаления записи
     * @return Response
     */
    public function goBackAfterDelete()
    {
        return $this->goBackCrud();
    }

    /**
     * Отрисовка в зависимости типа Аякс или обычная
     * @param string $view the view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string
     */
    public function renderIfAjax($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false,
            ];
            return parent::renderAjax($view, $params);
        }
        return $this->render($view, $params);
    }

    /**
     * @return string
     */
    public function getPermissionCategory()
    {
        $className = $this->modelClassName;
        /** @noinspection PhpUndefinedMethodInspection */
        return $className::tableName();
    }

    /**
     * @param ActiveRecord $model
     * @param string $typeAction
     */
    protected function afterFindModel($model, string $typeAction)
    {
        //empty
    }

    /**
     * @param ActiveRecord $model
     */
    protected function afterCreate($model)
    {
        //empty
    }
}