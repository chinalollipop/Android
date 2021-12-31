/**
 * zepto.animate.alias.js
 */
(function ($) {
    $.extend($.fn, {
        fadeIn: function (speed, easing, complete) {
            if (typeof(speed) === 'undefined') speed = 400;
            if (typeof(easing) === 'undefined') {
                easing = 'swing';
            } else if (typeof(easing) === 'function') {
                if (typeof(complete) === 'undefined') complete = easing;
                easing = 'swing';
            }

            $(this).css({
                display: 'block',
                opacity: 0
            }).animate({
                opacity: 1
            }, speed, easing, function () {
                // complete callback
                complete && typeof(complete) === 'function' && complete();
            });

            return this;
        },
        fadeOut: function (speed, easing, complete) {
            if (typeof(speed) === 'undefined') speed = 400;
            if (typeof(easing) === 'undefined') {
                easing = 'swing';
            } else if (typeof(easing) === 'function') {
                if (typeof(complete) === 'undefined') complete = easing;
                easing = 'swing';
            }

            $(this).css({
                opacity: 1
            }).animate({
                opacity: 0
            }, speed, easing, function () {
                $(this).css('display', 'none');
                // complete callback
                complete && typeof(complete) === 'function' && complete();
            });

            return this;
        },
        fadeToggle: function (speed, easing, complete) {
            return this.each(function () {
                var el = $(this);
                el[(el.css('opacity') === 0 || el.css('display') === 'none') ? 'fadeIn' : 'fadeOut'](speed, easing, complete)
            })
        },
        newshow: function (speed, easing, complete) { // 2019 新加
            if (typeof(speed) === 'undefined') speed = 400;
            if (typeof(easing) === 'undefined') {
                easing = 'swing';
            } else if (typeof(easing) === 'function') {
                if (typeof(complete) === 'undefined') complete = easing;
                easing = 'swing';
            }
            if(!top.indexslideHeight){
                top.indexslideHeight = $(this).parent().children().height();
                top.indexslideWidth = $(this).parent().children().width();
            }
            $(this).css({
                display: 'block',
                opacity: 0,
                height: 0,
                width: 0,
            }).animate({
                opacity: 1,
                height: top.indexslideHeight+'px',
                width: top.indexslideWidth+'px',
            }, speed, easing, function () {
                complete && typeof(complete) === 'function' && complete();
            });

            return this;
        },
        newhide: function (speed, easing, complete) { // 2019 新加
            if (typeof(speed) === 'undefined') speed = 400;
            if (typeof(easing) === 'undefined') {
                easing = 'swing';
            } else if (typeof(easing) === 'function') {
                if (typeof(complete) === 'undefined') complete = easing;
                easing = 'swing';
            }

            $(this).css({
                display: 'block',
                opacity: 1,
                height: top.indexslideHeight+'px',
                width: top.indexslideWidth+'px',

            }).animate({
                opacity: 0,
                height: 0,
                width: 0,
            }, speed, easing, function () {
                complete && typeof(complete) === 'function' && complete();
            });

            return this;
        },
        easeInOutCirc: function (x, t, b, c, d) {
            if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
            return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
        },

    })
})(Zepto);