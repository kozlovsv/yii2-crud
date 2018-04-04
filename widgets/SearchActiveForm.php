<?php
namespace kozlovsv\crud\widgets;

use yii\bootstrap\ActiveForm as BActiveForm;
use yii\web\View;

class SearchActiveForm extends BActiveForm
{

    /**
     * @var string
     */
    public $fieldClass = 'kozlovsv\crud\widgets\SearchActiveField';

    public $enableClientValidation = false;

    public $method = 'get';

    public $fieldConfig = [
        'template' => "{beginWrapper}\n{input}\n{endWrapper}",
        'horizontalCssClasses' => [
            'error' => false,
            'hint' => false,
        ],
    ];

    public $layout = 'inline';
    
    public $options = [
        'class' => 'search'
    ];

    /**
     *
     * @var string
     */
    public $pjaxContainer = 'pjax-content';

    /**
     *
     * @var string
     */
    public $eventSubmit = 'change';


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->options['dataPjax'] = 1;
        $this->registerJs();
    }
    
    protected function registerJs()
    {
        $selector = "#{$this->options['id']}";
        $this->view->registerJs("$('$selector').on('$this->eventSubmit', function (event) {
            $.pjax.submit(event, '#$this->pjaxContainer', {scrollTo: false});
        });", View::POS_END);
    }
}
