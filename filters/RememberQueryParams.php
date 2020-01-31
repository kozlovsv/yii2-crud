<?php
namespace kozlovsv\crud\filters;

use Exception;
use kozlovsv\crud\helpers\ReturnUrl;
use Yii;
use yii\base\ActionFilter;


class RememberQueryParams extends ActionFilter
{
    public static $sessionKey = 'crudRememberQueryString';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        try {
            $key = $this->getSessionKey($action->controller->route);
            $this->restoreQueryParams($key);
            $this->rememberQueryParams($key);
        } catch (Exception $e) {
            Yii::error('Ошибка при сохранении/восстановлении состояния QueryString. ' . $e->getMessage());
        }
        return true;
    }

    /**
     * @param string $route
     * @return string
     */
    private function getSessionKey($route) {
        return self::$sessionKey . '-' . intval(Yii::$app->getUser()->getId()) . '-' . $route;
    }

    private function restoreQueryParams($key)
    {
        if (empty(Yii::$app->request->get(ReturnUrl::RESTORE_QUERY_PARAM_NAME)) || !Yii::$app->session->has($key) || Yii::$app->request->isAjax) return;
        $queryString = Yii::$app->session->get($key);
        parse_str($queryString, $queryParams);
        if (empty($queryParams) || !is_array($queryParams)) return;
        $_GET = $queryParams;
    }

    private function rememberQueryParams($key)
    {
        if (!empty(Yii::$app->request->queryParams)) {
            $queryString = http_build_query(Yii::$app->request->queryParams);
            Yii::$app->session->set($key, $queryString);
        } else {
            Yii::$app->session->remove($key);
        }
    }
}
