<?php

namespace kozlovsv\crud\controllers;

use kozlovsv\helpers\ModelPermission;
use kozlovsv\helpers\ReturnUrl;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


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
     * Обновляемая, удаляемая или добавленная модель.
     * @return \yii\db\ActiveRecord
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $className = $this->modelClassName;
        /** @noinspection PhpUndefinedMethodInspection */
        $tableName = $className::tableName();
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => array_merge([
                    [
                        'actions' => ['index', 'view'],
                        'allow' => ModelPermission::canView($tableName),
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => ModelPermission::canCreate($tableName),
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => ModelPermission::canUpdate($tableName),
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => ModelPermission::canDelete($tableName),
                    ],
                ], $this->accessRules),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

   public function actionCreate(){
       $model = $this->createModel();
       $post = Yii::$app->request->post();
       if ($model->load($post) && $model->save()) {
           Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
           return $this->goBackAfterCreate();
       }
       return $this->renderIfAjax($this->createViewName, compact('model'));
   }

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
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные успешно сохранены');
            return $this->goBackAfterUpdate();
        }
        return $this->renderIfAjax($this->updateViewName, compact('model'));
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
     * @return \yii\db\ActiveRecord
     */
    public function createModel(){
        $model = new $this->modelClassName();
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
     * @return \yii\db\ActiveRecord
     */
    public abstract function getSearchModel();

    /**
     * Возврат назад
     * @return \yii\web\Response
     */
    public function goBack()
    {
        return ReturnUrl::goBack($this, $this->defaultBackUrl);
    }

    /**
     * Возврат после добавления записи
     * @return \yii\web\Response
     */
    public function goBackAfterCreate() {
        return $this->goBack();
    }

    /**
     * Возврат после обновления записи
     * @return \yii\web\Response
     */
    public function goBackAfterUpdate() {
        return $this->goBack();
    }

    /**
     * Возврат после удаления записи
     * @return \yii\web\Response
     */
    public function goBackAfterDelete() {
        return $this->goBack();
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
}
