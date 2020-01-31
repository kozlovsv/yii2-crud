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
     * Обновляемая, удаляемая или добавленная модель.
     * @return ActiveRecord
     */
    protected $model;

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
           if ($model->load($post) && $model->save()) {
               Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
               return $this->goBackAfterCreate();
           }
           return $this->renderIfAjax($this->createViewName, compact('model'));
       } catch (Exception $e) {
           if (YII_ENV_DEV) throw $e;
           Yii::error($e->getMessage());
           $message = 'При создании записи произошла ошибка. Обратитесь в службу поддержки.';
           Yii::$app->session->setFlash('error', $message);
           return $this->goBackAfterCreate();
       }
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
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Запись удалена');
            }
        } catch (Exception $e) {
            $message = 'Запись не может быть удалена, имеются связанные данные';
            Yii::$app->session->setFlash('error', $message);
        }
        return $this->goBackAfterDelete();
    }

    public function actionUpdate($id)
    {
        try {
            $model = $this->findModel($id);
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
                return $this->goBackAfterUpdate();
            }
            return $this->renderIfAjax($this->updateViewName, compact('model'));
        } catch (Exception $e) {
            if (YII_ENV_DEV) throw $e;
            Yii::error($e->getMessage());
            $message = 'При сохранении записи произошла ошибка. Обратитесь в службу поддержки.';
            Yii::$app->session->setFlash('error', $message);
            return $this->goBackAfterUpdate();
        }
    }

    public function actionIndex()
    {
        $searchModel = $this->getSearchModel();
        /** @noinspection PhpUndefinedMethodInspection */
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->indexViewName, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->renderIfAjax($this->viewViewName, compact('model'));
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
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
    public function createModel(){

        /** @var ActiveRecord $model */
        $model = new $this->modelClassName();
        if ($this->loadDefaultValue) $model->loadDefaultValues(true);
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
    public function goBackAfterCreate() {
        return $this->goBackCrud();
    }

    /**
     * Возврат после обновления записи
     * @return Response
     */
    public function goBackAfterUpdate() {
        return $this->goBackCrud();
    }

    /**
     * Возврат после удаления записи
     * @return Response
     */
    public function goBackAfterDelete() {
        return $this->goBackCrud();
    }

    /**
     * Отрисовка в зависимости типа Аякс или обычная
     * @param string $view the view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string
     */
    protected function renderIfAjax($view, $params = [])
    {
        if (Yii::$app->request->isAjax) return $this->renderAjax($view, $params);
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
}
