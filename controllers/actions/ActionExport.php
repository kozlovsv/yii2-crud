<?php

namespace kozlovsv\crud\controllers\actions;

use Exception;
use kozlovsv\crud\components\ExportGridViewToExcelXML;
use kozlovsv\crud\helpers\DateTimeHelper;
use kozlovsv\crud\models\IExportedSearchModelInterface;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

class ActionExport extends Action
{

    /**
     * @var IExportedSearchModelInterface
     */
    public $searchModel;

    public function init()
    {
        if (empty($this->searchModel))
            throw new InvalidConfigException('The field "searchModel" are required');
        parent::init();
    }

    /**
     * @var
     */
    public $gridViewColumns =[];

    public function run()
    {
        try {
            $searchModel = $this->searchModel;
            $query = $searchModel->searchQuery(Yii::$app->request->queryParams);
            $query->orderBy($searchModel::getDefaultSort());
            $export = new ExportGridViewToExcelXML([
                'gridViewColumns' => $this->gridViewColumns,
                'fileName' => $this->controller->id . '_export_' . DateTimeHelper::now('YmdHis'),
                'query' => $query,
                'limit' => Yii::$app->params['excelExportLimit'],
            ]);
            $export->run();
        } catch (Exception $e) {
            $message = 'При экспорте произошла ошибка. ' . $e->getMessage();
            Yii::error($e->getMessage());
            Yii::$app->session->setFlash('error', $message);
        }
        return $this->controller->goBack(['index']);
    }
}