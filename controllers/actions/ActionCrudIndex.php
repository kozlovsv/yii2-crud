<?php

namespace kozlovsv\crud\controllers\actions;

use kozlovsv\crud\classes\ISearchModelInterface;
use Yii;
use yii\base\Action;

class ActionCrudIndex extends Action
{
    /**
     * @var string
     */
    public string $viewName = 'index';

    /**
     * @var ISearchModelInterface
     */
    public ISearchModelInterface $searchModel;


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