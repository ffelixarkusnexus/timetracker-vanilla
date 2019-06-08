$(function() {

    var app = new Application();

    $('#increase').on('click', function() {
        app.increase();
    });

});

function Application() {

    // private

    var range = 0;

    var increment = function($bar, control, bgColor) {
        var $el = $bar.find(control);
        var data = $el.data('slide');

        data.width += 10;
        data.rightMargin = 0;
        data.background = "gray";

        $el.data('slide', data);
        $el.css({
            "width": data.width + "%",
            "background-color": bgColor,
            "margin-right": data.rightMargin + "%",
            "border-top-left-radius": "10px",
            "border-bottom-left-radius": "10px",
            "border-top-right-radius": "0",
            "border-bottom-right-radius": "0"
        });
    }

    var decrement = function($bar, control, bgColor) {
        var $el = $bar.find(control);
        var data = $el.data('slide');

        data.width -= 10;
        data.rightMargin += 10;
        data.background = "blue";

        $el.data('slide', data);
        $el.css({
            "width": data.width + "%",
            "background-color": bgColor,
            "margin-right": data.rightMargin + "%",
            "border-top-left-radius": "0",
            "border-bottom-left-radius": "0",
            "border-top-right-radius": "10px",
            "border-bottom-right-radius": "10px"
        });
    }

    // public

    var api = {};

    api.boot = function() {
        $('.tz-bar').each(function(index, el) {
            var $el = $(el);

            $el.find('.control1').data('slide', {
                width: 0,
                leftMargin: 0,
                rightMargin: 0,
                leftRadius: 0,
                rightRadius: 0,
                background: 'white',
            });

            $el.find('.control2').data('slide', {
                width: 0,
                leftMargin: 0,
                rightMargin: 0,
                leftRadius: 0,
                rightRadius: 0,
                background: 'white',
            });
        });
    }

    api.increase = function() {

        range += 10;

        $('.tz-bar').each(function(index, el) {

            var $bar = $(el);

            if (range > 0 && range <= 100) {
                increment($bar, '.control2', 'gray');
            }

            if (range > 100 && range <= 200) {
                decrement($bar, '.control2', 'blue');
                increment($bar, '.control1', 'green');
            }

            if (range > 200 && range <= 300) {
                decrement($bar, '.control1', 'gray');
            }

        });

        if (range == 300) {
            range = 0;
            api.boot();
        }
    };

    api.boot();

    return api;
}