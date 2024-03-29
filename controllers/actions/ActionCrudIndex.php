<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\models\ISearchModelInterface;
use Yii;
use yii\base\Action;
use yii\base\Model;

class ActionCrudIndex extends Action
{
    /**
     * @var string
     */
    public string $viewName = 'index';

    /**
     * @var ISearchModelInterface| Model
     */
    public ISearchModelInterface | Model $searchModel;


    /**
     * @return string
     */
    public function run()
    {
        $dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);
        return $this->controller->render($this->viewName, [
            'searchModel' => $this->searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}