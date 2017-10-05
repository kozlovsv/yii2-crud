<?php

namespace kozlovsv\crud\widgets;
use kozlovsv\widgets\ActiveForm;
use yii\bootstrap\Html;
use kozlovsv\helpers\ReturnUrl;
use Yii;
use yii\base\Widget;

/**
 * Виджет шаблона пустой (без полей) формы с кнопками для просмотра и редактирования записи.
 * Class CrudFormEmpty
 */
class CrudFormEmpty extends Widget
{
    /**
     * HTML id формы
     * @var string
     */
    public $idForm = '';

    /**
     * Массив настроек класса ActiveForm
     * @var string
     */
    public $activeFormConfig = [];

    /**
     * Класс ActiveForm
     * @var ActiveForm
     */
    public $activeForm;

    /**
     * Массив настроек полей ввода
     * [
     *      'defaultValue' => 1 //Значение по умолчанию
     *      'attribute' => 'name' //Наименование атрибута модели
     *      'format' => 'textInput' //Формат поля (текст, календерь). Вызывается в форме ActiveField->$format(...);
     *      'options' => ['class' => 'myClass'] //Массив HTML опций поля
     *      'items' => [] //Массив списков для выпадающих списков
     * ]
     * Если элемент массива $value - строка, то это интерпретируется как текстовое поле с атрибутом $value
     * @var array
     */
    public $fields = [];

    /**
     * Массив кнопок
     * @var array
     */
    public $buttons = [];

    /**
     * @inheritdoc
     */
    public function init() {
        $this->renderBeginForm();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $fields = $this->normalizeFields();
        $this->renderFields($fields);
        $this->renderButtons();
        $this->renderEndForm();
    }

    /**
     * Нормализовать  параметры
     * @return array
     */
    protected function normalizeFields()
    {
        $fields = !empty($this->fields) ? $this->fields : [];
        $fld = [];
        foreach ($fields as $field) {
            if (!is_string($field)) {
                $fld[] = $field;
            } else {
                $fld[] = [
                    'attribute' => $field,
                ];
            }
        }
        return $fld;
    }

    protected function needPjax(){
        return Yii::$app->request->isAjax;
    }

    /**
     * Нормализовать  параметры
     * @return string
     */
    protected function normalizeIdForm()
    {
        if (!empty($this->idForm)) return $this->idForm;
        return 'pjax-form';
    }

    /**
     * Нормализовать  параметры
     * @return array
     */
    protected function normalizeActiveFormConfig()
    {
        $arr = $this->needPjax()? [] : ['id' => $this->normalizeIdForm()];
        return array_merge($arr, $this->activeFormConfig);
    }

    /**
     * Отрисовка начала формы
     */
    protected function renderBeginForm()
    {
        //Для нормальной работы круд в диалоговых окнах нужен Pjax контейнер
        if ($this->needPjax()) {
            Pjax::begin([
                'id' => $this->normalizeIdForm(),
                'enablePushState' => false,
            ]);
        }
        $this->activeForm = ActiveForm::begin($this->normalizeActiveFormConfig());
        //Задаем параметр обновления родительского окна после закрытия
        if (Yii::$app->request->isAjax && ReturnUrl::isSetReturnUrl()) {
            $this->view->registerJs('var parent_window_reloaded = 1');
        }
    }

    /**
     * Отрисовка окончания формы
     */
    protected function renderEndForm()
    {
        if (ReturnUrl::isSetReturnUrl()) echo Html::hiddenInput(ReturnUrl::REQUEST_PARAM_NAME, ReturnUrl::getReturnUrlParam());
        ActiveForm::end();
        if ($this->needPjax()) Pjax::end();
    }

    /**
     * Отрисовка панели кнопок
     */
    protected function renderButtons()
    {
        $buttons = $this->normalizeButtons();
        echo $this->render('_form_buttons', compact('buttons'));
    }

    /**
     * Отрисовка полей ввода. Для переопределения в классах потомках.
     * @param array $fields
     */
    protected function renderFields($fields)
    {
        //Empty
    }

    protected function normalizeButtons()
    {
        if (!empty($this->buttons)) return $this->buttons;
        return self::standartButtons();
    }

    /**
     * Массив HTML стандартных кнопок формы редактирования
     * @return array
     */
    public static function standartButtons() {
        return [
            Html::submitButton('Сохранить', ['class' => 'btn btn-primary']),
            Html::a('Отмена', ['index'], ['class' => 'btn btn-default form-cancel']),
        ];
    }
}