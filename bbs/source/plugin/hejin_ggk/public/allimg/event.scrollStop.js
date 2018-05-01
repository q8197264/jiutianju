(function ($, win) {

    function registerScrollStop() {
        $(win).on('scroll', $.debounce(80, function () {
            $(win).trigger('scrollStop');
        }, false));
    }

    function backEventOffHandler() {
        $(win).off('scroll');
        registerScrollStop();
    }
    registerScrollStop();

    $(win).on('pageshow', function (e) {
        e.persisted && $(win).off('touchstart', backEventOffHandler).one('touchstart', backEventOffHandler);
    });

})(Zepto, window);