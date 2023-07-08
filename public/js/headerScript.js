function initHeader() {
    // lIn = '';
    var logButton = $("#logInOut");
    if (lIn != '') {
        logButton.removeClass("noLogIn");
        logButton.addClass("logIn");
        logButton.text("Log-Out");
        $("#accountDetails").text(lIn);
        $('accountPhoto').attr('src', '');
    } else {
        logButton.removeClass("logIn");
        logButton.addClass("noLogIn");
        logButton.text("Log-In");
        $("#accountDetails").text('');
    }
}

var lSelected = "En";

function switchLang(lang) {
    if (lang != lSelected)
        lSelected = lang;
    if (lSelected == "En") {
        $("#EnLanguage").addClass("lSelected");
        $("#ArLanguage").removeClass("lSelected");

    }
    if (lSelected == "Ar") {
        $("#ArLanguage").addClass("lSelected");
        $("#EnLanguage").removeClass("lSelected");
    }
}
$(window).on("scroll",
    () => {
        var c = window.pageYOffset;
        c = Math.floor(Math.min(Math.max(100 - c, 0), 100));
        var x, y, z, p;
        if (c <= 5) {
            x = 0;
            y = 30;
            z = 90;
            p = 420;
        }
        if (c >= 95) {
            z = 170;
            x = 100;
            y = 0;
            p = 500;


        }
        $("#logoimg")
            .stop()
            .css({
                'transition': 0.3 + 's',
                'height': x + '%',
                'width': x + '%'
            });
        // $("#logInOutContainer").css('left', Math.min((Math.max(4.5 * (c - 100), -100 * 1.7) + 20), 0) - 0.2 * (100 - c) + "px");
        // $("#logInOutContainer").css('bottom', (4.5 - 1 * Math.max(4.5 * (100 - c) / 10, -100 * 1.7) / 10) + "px");
        $("#logInOutContainer")
            .css({
                'transition': 0.3 + 's',
                'bottom': (-y / 25) + "px",
                'left': -y * 6 + "px"
            });

        $("#languageSelector")
            .css({
                'transition': 0.3 + 's',
                'top': (-y * 2) + "px",
                'margin-bottom': (-y * 2) + "px"
            });

        $('#infoBar')
            .css({
                'transition': 0.3 + 's',
                'top': z + "px",
            });

        $('#submitBar')
            .css({
                'transition': 0.3 + 's',
                'top': p + "px",
            });
    }
);

$('#headBody').ready(initHeader);