<?php

namespace kozlovsv\crud\controllers\actions;

use Yii;
use yii\web\Response;

/**
 * @method render($view, $params = [])
 * @method renderAjax($view, $params = [])
 */
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
        if (Yii::$app->request->isAjax) {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset' => false,
            ];
            /** @noinspection PhpUndefinedMethodInspection */
            return parent::renderAjax($view, $params);
        }
        return $this->render($view, $params);
    }
}