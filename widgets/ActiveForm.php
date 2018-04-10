<?php
namespace kozlovsv\crud\widgets;
use kozlovsv\crud\helpers\ReturnUrl;
use Yii;
use yii\helpers\Html;

/**
 * Class ActiveForm
 */
class ActiveForm extends  \kartik\form\ActiveForm
{
    /**
     * @var bool
     */
    public $enableClientValidation = false;

    /**
     * Валидация на фокусе
     */
    public $validateOnBlur = false;

    /**
     * Валидация на фокусе
     */
    public $validateOnChange = false;

    /**
     * @inheritdoc
     */
    public $type = self::TYPE_HORIZONTAL;

    /**\
     * @var array
     */
    public $fieldConfig = [
        'horizontalCssClasses' => [
            'label' => 'col-lg-3 col-md-3 col-sm-3',
            'wrapper' => 'col-lg-9 col-md-9 col-sm-9',
            'error' => '',
            'hint' => '',
        ],
    ];

    /**
     * @var array
     */
    public $options = [
        'role' => 'form',
        'data' => ['pjax' => 1],
    ];

    /**
     * Конфиг для виджета Pjax
     * @var array
     */
    public $pjaxConfig = [
        'id' => 'pjax-form',
        'enablePushState' => false,
    ];

    /**
     * @inheritdoc
     */
    public function init() {
        //Для нормальной работы круд в диалоговых окнах нужен Pjax контейнер
        if ($this->needPjax()) {
            Pjax::begin($this->pjaxConfig);
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run() {
        if (ReturnUrl::isSetReturnUrl()) echo Html::hiddenInput(ReturnUrl::REQUEST_PARAM_NAME, ReturnUrl::getReturnUrlParam());
        parent::run();
        if ($this->needPjax()) Pjax::end();
    }



    protected function needPjax(){
        return Yii::$app->request->isAjax;
    }

    /**
     * This registers the necessary JavaScript code.
     * @since 2.0.12
     */
    public function registerClientScript()
    {
        parent::registerClientScript();
        //Задаем параметр обновления родительского окна после закрытия
        if ($this->needPjax() && ReturnUrl::isSetReturnUrl()) {
            $this->view->registerJs('var parent_window_reloaded = 1');
        }
    }
}
