<?php

namespace kozlovsv\crud\widgets;

use yii\base\Widget;
use yii\widgets\Pjax;

/**
 * Каркас страницы Index
 * Class ActiveForm
 */
class IndexPage extends Widget
{
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->renderBeginPage();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->renderEndPage();
    }

    /**
     * Отрисовка начала формы
     */
    protected function renderBeginPage()
    {
        Pjax::begin([
            'id' => 'pjax-content',
            'formSelector' => false,
        ]);
    }

    /**
     * Отрисовка закрывающих тегов формы
     */
    private function renderEndPage()
    {
        Pjax::end();
    }
}
