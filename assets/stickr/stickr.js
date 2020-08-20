// noinspection JSUnresolvedVariable
(function($)
{
    $.stickr = function(o) {
        o = $.extend({   // настройки по умолчанию
            time: 8000, // количество мс, которое отображается сообщение
            speed: 'fast', // скорость анимации
            note: null, // текст сообщения
            className: null, // класс, добавляемый к сообщению
            sticked: false, // отключить автоматическое скрытие
        }, o);
        let mainId = '#jquery-stickers';
        let stickers = $(mainId); // начинаем работу с главным элементом
        if (!stickers.length) { // если его ещё не существует
            $('body').prepend('<div id="jquery-stickers"></div>'); // добавляем его
            stickers = $(mainId);
        }
        stickers.css('position','fixed').css({right:'auto',left:'auto',top:'auto',bottom:'0'}).css('z-index', '10000'); // позиционируем

        let stick = $('<div class="stickr" style="display: none;"></div>'); // создаём стикер
        stickers.append(stick); // добавляем его к родительскому элементу

        if (o.className) stick.addClass(o.className); // если необходимо, добавляем класс

        stick.html(o.note).slideDown(); // вставляем сообщение

        if (!o.sticked) { // если сообщение закреплено
            setTimeout(function(){ // устанавливаем таймер на необходимое время
                stick.fadeOut(o.speed, function(){ // затем скрываем стикер
                    $(this).remove(); // по окончании анимации удаляем его
                });
            }, o.time);
        }

        let exit = $('<span class="stickr-close"></span>');  // создаём кнопку выхода
        stick.prepend(exit); // вставляем её перед сообщением
        exit.bind('click' ,function(){  // при клике
            stick.fadeOut(o.speed, function(){ // скрываем стикер
                $(this).remove(); // по окончании анимации удаляем его
            })
        });
    };
})(jQuery);
