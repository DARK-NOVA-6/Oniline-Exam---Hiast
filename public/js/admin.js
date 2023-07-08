$("#drawer")
    .on('scroll',
        () => {
            clearTimeout($.data(this, "scrollTimer"));

            $("#drawer")
                .removeClass("hideScroll");

            $.data(this, "scrollTimer",
                setTimeout(
                    () => {
                        $("#drawer")
                            .addClass("hideScroll");
                    },
                    1000
                )
            );
        }
    )
    .hover(() => onHoverDrawer(), () => onUnHoverDrawer());


$(document)
    .ready(
        () => {
            $(".footer")
                .css('margin-left', 121 + 'px');
        }
    )
    .on('click',
        function(e) {
            if (e.target.className.lastIndexOf('destroying') == -1 ||
                $('.subMenu').length > 1) {
                $('.subMenu').remove();
            }
        }
    );

function scrollDrawerUp() {
    $("#drawer")
        .scrollTop("160");
}


function posDivByDiv(percent, div1, div2) {
    captionWidth = parseFloat($(div1)
        .css('width'));

    tableWidth = parseFloat($(div2)
        .css('width'));

    captionPadding = parseFloat($(div1)
        .css('padding'));

    newMargin = percent - 50 * (
        (captionWidth + (captionPadding * 2)) / tableWidth) + '%';

    $(div1)
        .css('margin-left', newMargin);
}

function onHoverDrawer() {
    texts.forEach(
        element => {
            element.hover();
        }
    );
}

function onUnHoverDrawer() {
    texts.forEach(
        element => {
            element.unhover();
        }
    );
}