<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 05.10.2017
 * Time: 19:15
 */

namespace kozlovsv\crud\widgets;


use yii\bootstrap\Modal;

/**
 * Class Dialog
 * @package app\widgets
 */
class Dialog extends Modal
{
    /**
     * @var string
     */
    public $size = self::SIZE_LARGE;

    /**
     * @var bool
     */
    public $toggleButton = false;

    /**
     * @var string
     */
    public $attribute = 'data-modal';

    public $containerCssClassName = 'modal-container';

    public function init()
    {
        /** Обязательно в диалоге должен стоять 'tabindex' => -1 иначе автофокус работать не будет.
         * class = ''  чтобы не добавлялся класс fade он делает анимацию.
         */
        $this->options = array_merge($this->options, ['tabindex' => -1, 'class' => '']);
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJs();
        parent::run();
    }

    /**
     * Клиентские скрипты
     */
    public function registerJs()
    {
        $view = $this->view;
        $selector = "#{$this->getId()}";
        $js = '
        $("document").ready(function() {
            $.ajaxSetup({
                cache: true
            });
            $(document).on("click", "[' . $this->attribute . ' = 1]", function() {
                $.ajax({
                    method: "get",
                    url: $(this).attr("href"),
                    dataType: "html"
                }).done(
                function(response, status, xhr) {
                     if (response) { 
                        var ct = xhr.getResponseHeader("content-type") || "";
                        if (ct.indexOf("html") > -1) {
                            $("' . $selector . ' .modal-body").html(response);
                            var width = $(response).closest(".'. $this->containerCssClassName.'").attr("data-modal-width");
                            if (width > 0) {
                                var value = width + "px";
                                $(".modal-dialog").css("max-width", value);
                            } else {
                                $(".modal-dialog").css("max-width", "");
                            }
                            $("' . $selector . '").modal();
                        } else if (ct.indexOf("json") > -1)
                        {
                            $("' . $selector . '").modal("hide");
                        }
                    }        
                 });
                return false;
            });
            $("' . $selector . '").on("hidden.bs.modal", function (e) {
              if (typeof parent_window_reloaded != "undefined") location.reload();
            });
            $("' . $selector . '").on("shown.bs.modal", function(e) {
                $( "input[autofocus]" ).focus();
            });
        });
        ';
        $view->registerJs($js, $view::POS_END);
    }
}