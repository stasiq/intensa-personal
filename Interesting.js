
//Горизонтальный скролл, скролл до активного эдемента
if (scrollContainer) {
    function scrollToActive() {

        var containerOuterWidth = $(scrollContainer).outerWidth();

        var itemOuterWidth = $(activeMenuLink).outerWidth(); // узнаем ширину текущего элемента (width + padding)
        console.log(itemOuterWidth);
        var itemOffsetLeft = $(activeMenuLink).offset().left; // узнаем значение отступа слева в контейнере у текущего элемента
        console.log(itemOffsetLeft);
        var containerScrollLeft = $(scrollContainer).scrollLeft(); // узнаем текущее значение скролла
        console.log(containerScrollLeft);

        var positionCetner = (containerOuterWidth / 2 - itemOuterWidth / 2); // рассчитываем позицию центра
        console.log(positionCetner);
        var scrollLeftUpd = containerScrollLeft + itemOffsetLeft - positionCetner; // рассчитываем положение скролла относительно разницы отступа элемента и центра контейнера
        console.log(scrollLeftUpd);
        // анимируем
        scrollContainer.scrollLeft = scrollLeftUpd;
        $(scrollContainer).animate({
            scrollLeft: scrollLeftUpd
        }, 10);

    }
    scrollToActive()

    function scrollHorizontally(e) {
        e = window.event || e;
        var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
        scrollContainer.scrollLeft -= (delta*54); // Multiplied by 10
        e.preventDefault();
    }
    if (scrollContainer.addEventListener) {
        // IE9, Chrome, Safari, Opera
        scrollContainer.addEventListener("mousewheel", scrollHorizontally, false);
        // Firefox
        scrollContainer.addEventListener("DOMMouseScroll", scrollHorizontally, false);
    } else {
        // IE 6/7/8
        scrollContainer.attachEvent("onmousewheel", scrollHorizontally);
    }
}

//КНОПКА СКРОЛЛ НАВЕРХ
$('.scroll-up').click(function() {
    if (window.screen.width > 799) {
        $("html, body").animate({
            scrollTop:0
        },1000);
    }
});

$(window).scroll(function() {
    if (window.screen.width > 799) {
        if ($(this).scrollTop() > 200) {
            $('.scroll-up').fadeIn().css("display", "flex");
        } else {
            $('.scroll-up').fadeOut();
        }
    }
});