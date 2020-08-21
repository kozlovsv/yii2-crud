<?php

namespace kozlovsv\crud\widgets;

use Exception;
use Yii;
use yii\bootstrap\Widget;

abstract class Menu extends Widget
{
    /**
     * Префикс кэш-ключа
     * @var string
     */
    const CACHE_PREFIX = 'menu-user';

    /**
     * Флаг - кэшировать или нет меню.
     * @var bool
     */
    public $cache = true;

    protected $navOptions = ['class' => 'navbar-nav navbar-left'];

    /**
     * Собрать меню
     * Пункты меню кэшируются для каждого юзера!
     * @return string
     * @throws Exception
     * @return string
     */
    public function run()
    {
        $key = $this->getCacheKey(Yii::$app->user->id);

        if ($this->cache) {
            if (Yii::$app->cache->exists($key)) {
                $items = Yii::$app->cache->get($key);
            } else {
                $items = $this->getItems();
                Yii::$app->cache->add($key, $items);
            }
        } else {
            $items = $this->getItems();
        }

        return Nav::widget([
            'options' => $this->navOptions,
            'items' => $items,
        ]);
    }


    /**
     * Получить кэш-ключ для меню
     * @param $id int
     * @return string
     */
    public static function getCacheKey($id)
    {
        return self::CACHE_PREFIX . $id;
    }

    /**
     * Получить все пункты меню
     * @return array
     */
    protected abstract function getItems();

    /**
     * @param $id
     */
    public static function clearCache($id)
    {
        Yii::$app->cache->delete(self::getCacheKey($id));
    }
}