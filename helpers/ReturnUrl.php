<?php
namespace kozlovsv\crud\helpers;
use Yii;
use yii\helpers\Url;
use yii\web\Request;

/**
 * Вспомогательный класс для работы с параметрами возврата после закрытия формы
 * Class ReturnUrl
 */
class ReturnUrl {
    /**
     * Наименование GET или POST параметра, который указывает куда редиректиться после закрытия
     */
    const REQUEST_PARAM_NAME = 'returnUrl';

    const RESTORE_QUERY_PARAM_NAME = 'crudRestore';

    /**
     * Получить значение параметра ReturnUrl
     * @return string
     */
    public static function getReturnUrlParam()
    {
        $val = Yii::$app->request->get(self::REQUEST_PARAM_NAME, '');
        if (!$val) Yii::$app->request->post(self::REQUEST_PARAM_NAME, '');
        return $val;
    }

    /**
     * Получить значение параметра ReturnUrl
     * @param string $url
     */
    public static function setReturnUrlParam($url)
    {
        $params = Yii::$app->request->queryParams;
        $params[self::REQUEST_PARAM_NAME] = $url;
        Yii::$app->request->setQueryParams($params);
    }

    /**
     * Получить URL возврата.
     * Формат URL для URL::to()
     * @param string|array $defaultUrl URL для возврата по умолчанию
     * @return array|string
     */
    public static function getBackUrl($defaultUrl = ['index']) {
        if (self::isSetReturnUrl()) return self::getReturnUrlParam();
        if (!empty(Yii::$app->request->referrer) && Yii::$app->request->isAjax)  return Yii::$app->request->referrer;
        //Добавляем гет параметр необходимости восстановится.
        if (!Yii::$app->request->isAjax) {
            if (is_array($defaultUrl)) {
                $defaultUrl[self::RESTORE_QUERY_PARAM_NAME] = 1;
            } else {
                $defaultUrl .= ((strpos($defaultUrl, '?') === false) ? '?' : '&') . self::RESTORE_QUERY_PARAM_NAME . '=1';
            }
        }
        return is_array($defaultUrl)? $defaultUrl : [$defaultUrl];
    }

    public static function isSetReturnUrl() {
        return !empty(self::getReturnUrlParam());
    }

    /**
     * Редирект назад
     * @param \yii\web\Controller $controller
     * @param string $defaultUrl URL для возврата по умолчанию
     * @return \yii\web\Response
     */
    public static function goBack($controller, $defaultUrl) {
        $url = self::getBackUrl($defaultUrl);
        //Если возврат нужен по параметру returnUrl и в Ajax запросе (диалоговом окне) то вместо Redirect делам отображение контроллера.
        if (Yii::$app->request->isPjax && self::isSetReturnUrl()) {
            $request = new Request();
            $request->setUrl(Url::to(parse_url($url,  PHP_URL_PATH)));
            $routeParams = Yii::$app->getUrlManager()->parseRequest($request);
            if (!empty($routeParams)) {
                $route = $routeParams[0];
                $params = empty($routeParams[1])? [] : $routeParams[1];
                return Yii::$app->runAction($route, $params);
            }
        }
        return $controller->redirect($url);
    }

    /**
     * Получить параметры для формирования URL возврата на $action (view, update ...) с параметрами 'id' => $model->getPrimaryKey()
     * @param \yii\db\ActiveRecord $model
     * @param string $action
     * @return array
     */
    public static function formatReturnUrlParam($model, $action = 'view'){
        return [ReturnUrl::REQUEST_PARAM_NAME => Url::to([$action, 'id' => $model->getPrimaryKey()])];
    }
}
