<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\web\Controller;
use yii\web\Response;

trait RenderIfAjaxTrait
{
    /**
     * Отрисовка в зависимости типа Аякс или обычная
     * @param string $view the view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string|Response
     */
    public function renderIfAjax($view, $params = [])
    {
        if ($this instanceof Controller) {
            $controller = $this;
        } else {
            $controller = $this->controller;
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false,
            ];
            return $controller->renderAjax($view, $params);

        }
        return $controller->render($view, $params);
    }
}