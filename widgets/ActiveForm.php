<?php
namespace kozlovsv\crud\widgets;

/**
 * Class ActiveForm
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    /**
     * @var string
     */
    public $fieldClass = 'kozlovsv\crud\widgets\ActiveField';

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
     * @var string
     */
    public $layout = 'horizontal';

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
        'data' => [
            'pjax' => 1,
        ],
    ];

    /**
     * @inheritdoc
     * @return ActiveField|\yii\bootstrap\ActiveField
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}