<?php

namespace kozlovsv\crud\widgets;

use yii\bootstrap\Html;

class Nav extends \yii\bootstrap\Nav
{
    /**
     * @var bool
     */
    public $activateItems = true;

    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     */
    public $activateParents = true;

    /**
     * @var bool
     */
    public $encodeLabels = false;

    /**
     * @var bool
     */
    public $hideEmpty = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->items = $this->normalizeItems($this->items);

        parent::init();
    }

    /**
     * Магия, которая скрывает пустые пункты меню,
     * если у них есть дети, но с правами не шмагля
     * @param array $items
     * @return array
     */
    protected function normalizeItems($items)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) continue;

            if (isset($item['badge']) && $item['badge']) {
                $badge = Html::tag('span', $item['badge'], ['class' => 'badge']);
                $items[$i]['label'] = "{$item['label']} {$badge}";
            }
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items']);
                if (empty($items[$i]['items']) && $this->hideEmpty) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
        }

        return array_values($items);
    }
}