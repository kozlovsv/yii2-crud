<?php

namespace kozlovsv\crud\widgets;

use Yii;
use yii\bootstrap\Widget;

abstract class Menu extends Widget
{
    /**
     * Флаг - кэшировать или нет меню.
     * @var bool
     */
    public $cache = true;


    /**
     * Префикс кэш-ключа
     * @var string
     */
    protected $keyPrefix = 'menu-user';

    protected $navOptions = ['class' => 'navbar-nav navbar-left'];

    /**
     * Собрать меню
     * Пункты меню кэшируются для каждого юзера!
     * @return string
     * @throws \Exception
     * @return string
     */
    public function run()
    {
        $key = $this->getCacheKey();

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
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->keyPrefix . Yii::$app->user->id;
    }

    /**
     * Получить все пункты меню
     * @return array
     */
    protected abstract function getItems();
}