<?php

namespace kozlovsv\crud\widgets;

use kozlovsv\crud\helpers\ReturnUrl;
use Yii;
use yii\helpers\Html;

/**
 * Class ActiveForm
 */
class ActiveForm extends \kartik\form\ActiveForm
{

    public $pjaxId = 'pjax-form';

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
    public $pjaxConfig = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (ReturnUrl::isSetReturnUrl()) echo Html::hiddenInput(ReturnUrl::REQUEST_PARAM_NAME, ReturnUrl::getReturnUrlParam());
        return parent::run();
    }

    /*
     * Данная функция нужна чтобе обернуть нормально форму ActiveForm в PJAX контейнер.
     * Так как внутри родной ActiveForm все формируется в строку. А в Pjax данные идут в буфер через ECHO.
     * То возникает два варианта. Либо мы оборачиваем в контейнер снаружи вызова ActiveForm::begin()ActiveForm::end()
     * Либо вот такие пляски с бубном.
     */
    public function afterRun($result)
    {
        ob_start();
        ob_implicit_flush(false);

        //Для нормальной работы круд в диалоговых окнах нужен Pjax контейнер
        if ($this->needPjax()) {
            $this->initPjaxConfig();
            Pjax::begin($this->pjaxConfig);
        }
        //Запускаем построение формы
        $result = parent::afterRun($result);
        //Кидаем в буфер
        echo $result;

        if ($this->needPjax()) Pjax::end();

        //Получаем форму, обернутую в PJAX контейнер.
        $result = ob_get_clean();

        return $result;
    }


    protected function needPjax()
    {
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

    protected function initPjaxConfig()
    {
        $this->pjaxConfig = array_merge(
            [
                'id' => $this->pjaxId,
                'enablePushState' => false,
            ],
            $this->pjaxConfig
        );
    }
}
