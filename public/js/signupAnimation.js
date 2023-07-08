var selectedCard;

function selectCard(idCard) {
    $("#" + idCard)
        .removeClass("cardAnimate");
    if (selectedCard != undefined) {
        $("#" + selectedCard)
            .addClass("cardAnimate");
        $("#" + selectedCard)
            .removeClass("selectedCardAnimate");
    }
    if (selectedCard != idCard) {
        $("#" + idCard)
            .addClass("selectedCardAnimate");
        selectedCard = idCard;
    } else {
        selectedCard = undefined;
    }
}

function hideAccTypeChoser() {
    for (id of['adminCard', 'tcAdminCard', 'studentCard', 'selectButton']) {
        if (id != selectedCard) {
            $('#' + id)
                .addClass('scaleDownElementAnimate');
        }
    }
    setTimeout(() => {
        $("#" + selectedCard)
            .addClass("hideOverlayAnimate");
        setTimeout(() => {
            $("#" + selectedCard)
                .addClass("closeElementAnimate");
            $("#accTypeChosr")
                .addClass("closeContainerAnimate");
        }, 800);
    }, 700);
}

function showSignupForm() {
    if (selectedCard == 'tcAdminCard') {
        $("#tcContainer")
            .attr('style', '');
        $("#signupForm")
            .attr('style', 'height:675px;');
    } else {
        $("#signupForm")
            .attr('style', 'height:617px;');
        $("#tcContainer")
            .attr('style', 'display: none;');
    }


    $("#signupForm")
        .addClass('closeElementAnimate closeContainerAnimate');
    var role = selectedCard == 'adminCard' ? 'Admin' : selectedCard == 'tcAdminCard' ? 'Test Center Admin' : 'Student';
    var role_title = selectedCard == 'adminCard' ? 'ADMIN' : selectedCard == 'TC_ADMIN' ? 'Test Center Admin' : 'STUDENT';
    $('#roleAccount')
        .val(role_title)
        .attr('value', role_title);
    $("#accountType")
        .text(role);
    $("#roleAccount")
        .attr('value', role)
        .val(role);
    // alert(role);
    setTimeout(() => {
        $("#signupForm")
            .removeClass('closeElementAnimate closeContainerAnimate');
    }, 1);
}

function showAccTypeChoser() {
    $("#accTypeChosr")
        .attr('style', '');
    setTimeout(() => {
        $("#accTypeChosr")
            .removeClass("closeContainerAnimate");
        $("#" + selectedCard)
            .removeClass("closeElementAnimate hideOverlayAnimate");
        for (id of['adminCard', 'tcAdminCard', 'studentCard', 'selectButton']) {
            $('#' + id)
                .removeClass('scaleDownElementAnimate');
        }
        $('#' + selectedCard)
            .removeClass('selectedCardAnimate');
        $("#" + selectedCard)
            .addClass("cardAnimate");
        selectedCard = undefined;
    }, 1);
}

function getForm() {
    if (selectedCard != undefined) {
        hideAccTypeChoser();
        setTimeout(
            () => {
                $("#accTypeChosr")
                    .attr('style', 'display:none;');
                showSignupForm();
            },
            2310
        );
    } else {
        $("#accTypeChosr")
            .addClass('shakeElementAnimate');
        setTimeout(
            () => {
                $("#accTypeChosr")
                    .removeClass('shakeElementAnimate');
            },
            300
        );
    }
}

function hideSignupForm() {
    $("#signupForm")
        .addClass('closeElementAnimate closeContainerAnimate');
}

function getAccTypeChoser() {
    hideSignupForm();
    setTimeout(() => {
        $("#signupForm")
            .attr('style', 'display:none;');
        showAccTypeChoser();
    }, 900);
}