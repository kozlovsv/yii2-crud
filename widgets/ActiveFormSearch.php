<?php
namespace kozlovsv\crud\widgets;

use yii\web\View;

class ActiveFormSearch extends \kartik\form\ActiveForm
{

    /**
     * @var string
     */
    public $fieldClass = 'kozlovsv\crud\widgets\ActiveFieldSearch';

    public $enableClientValidation = false;

    public $method = 'get';

    public $fieldConfig = [
        'template' => "{beginWrapper}\n{input}\n{endWrapper}",
        'horizontalCssClasses' => [
            'error' => false,
            'hint' => false,
        ],
    ];

    public $type = self::TYPE_INLINE;
    
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
     * Переопределяем параметр action
     * @var array
     */
    public $action = ['index'];

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
