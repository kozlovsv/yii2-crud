<?php

namespace kozlovsv\crud\helpers;

use Yii;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

/**
 * Class ReturnUrl
 *
 * The ReturnUrl class provides methods for handling return URLs in a web application.
 */
class ReturnUrl
{
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
    public static function getBackUrl($defaultUrl = ['index'])
    {
        if (self::isSetReturnUrl()) return self::getReturnUrlParam();
        if (!empty(Yii::$app->request->referrer) && Yii::$app->request->isAjax) return Yii::$app->request->referrer;
        //Добавляем гет параметр необходимости восстановится.
        if (!Yii::$app->request->isAjax) {
            if (is_array($defaultUrl)) {
                $defaultUrl[self::RESTORE_QUERY_PARAM_NAME] = 1;
            } else {
                $defaultUrl .= ((!str_contains($defaultUrl, '?')) ? '?' : '&') . self::RESTORE_QUERY_PARAM_NAME . '=1';
            }
        }
        return is_array($defaultUrl) ? $defaultUrl : [$defaultUrl];
    }

    public static function isSetReturnUrl()
    {
        return !empty(self::getReturnUrlParam());
    }

    /**
     * Редирект назад
     * @param Controller $controller
     * @param string|array $defaultUrl URL для возврата по умолчанию
     * @param bool $onlyRender Флаг принудительной отрисовки формы, без редиректа. Возвращает только отрендеренный HTML код
     * @return Response
     * @throws Exception
     * @throws InvalidRouteException
     */
    public static function goBack($controller, $defaultUrl, $onlyRender = false)
    {
        $url = self::getBackUrl($defaultUrl);
        //Если возврат нужен по параметру returnUrl и в Ajax запросе (диалоговом окне) то вместо Redirect делам отображение контроллера.
        if ((Yii::$app->request->isPjax && self::isSetReturnUrl()) || $onlyRender) {
            $request = new Request();
            $request->setUrl(Url::to(parse_url($url, PHP_URL_PATH)));
            $routeParams = Yii::$app->getUrlManager()->parseRequest($request);
            if (!empty($routeParams)) {
                $route = $routeParams[0];
                $params = empty($routeParams[1]) ? [] : $routeParams[1];
                return Yii::$app->runAction($route, $params);
            }
        }
        return $controller->redirect($url);
    }

    /**
     * Добавляет параметр ID к URL. Если параемтр ID уже задан, то ничего не делает.
     * @param array|string $url
     * @param int | null $id
     * @return array
     */
    public static function addIdToUrl($url, $id): array
    {
        if (!is_array($url)) $url = [$url];
        if (!is_null($id) && !isset($url['id'])) {
            $url['id'] = $id;
        }
        return $url;
    }

    /**
     * Adds a return URL parameter to the given URL.
     *
     * @param array|string $url The URL to modify. If a string is provided, it will be converted to an array.
     * @param string $returnUrl The return URL to append to the URL.
     * @param bool $crudRestore Optional. Whether to add a restore query parameter for CRUD operations. Defaults to false.
     * @return array The modified URL array with the return URL parameter added.
     */
    public static function withReturnParam($url, $returnUrl, $crudRestore= false) {
        if (!is_array($url)) $url = [$url];
        $url[ReturnUrl::REQUEST_PARAM_NAME] = Url::to($returnUrl);
        if ($crudRestore) {
            $url[ReturnUrl::RESTORE_QUERY_PARAM_NAME] = 1;
        }
        return $url;
    }
}

