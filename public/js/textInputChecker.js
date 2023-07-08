var correctImg = LocalURL + 'public/icon/checked.svg';
var incorrectImg = LocalURL + 'public/icon/unchecked.svg';
var emptyImg = LocalURL + 'public/icon/hint.svg';
var loadingImg = LocalURL + 'public/icon/loading.svg';
var STRENGTH_BAR_EXTENSION = 'ProgBar';
var TIPS_EXTENSION = 'Tips';
var SHOW_ERROR_CLASS = 'tipsHover';
class Condition {
    constructor(regexp, should) {
        this.regexp = regexp;
        this.should = should;
    }
    test(inp) {
        return this.regexp.test(inp) == this.should;
    }
}
class TextCondition extends Condition {
    constructor(regexp, flagMsg, should, makeChange) {
        super(regexp, should);
        this.flagMsg = flagMsg;
        this.makeChange = makeChange;
    }
    getMatched(inp) {
        while (!super.test(inp))
            inp = inp.slice(0, -1);
        return inp;
    }
}
class PasswordCondition extends Condition {
    constructor(regexp, score) {
        super(regexp, true);
        this.score = score;
    }
    evaluate(inp) {
        return this.score[super.test(inp) ? 1 : 0];
    }
    getScore() {
        return this.score[1];
    }
}
var Msg = {
    PASSWORD: {
        EMPTY: "empty password",
        WEAK: "weak password",
        FINE: "fine"
    },
    CONF_PASSWORD: {
        EMPTY: "empty confirm",
        NOT_MATCHED: "not matched",
        MATCHED: "ok,, matched"
    },
    EMAIL: {
        EMPTY: "empty email",
        FINE: "fine email",
        END_WITH_DOT: "an email can't end with '.'",
        START_WITH_DOT: "an email can't start with '.'",
        START_WITH_NUMBER: "an email can't start with number",
        INVALID_FORMAT: "Only English alphabet and numbers are allowed",
        TWO_CONSECUTIVE_DOTS: "two consecutive dots aren't allowed",
        TAKEN_EMAIL: "this email is unavailable"
    },
    NAME: {
        EMPTY: "empty name",
        FINE: "fine name",
        INVALID_FORMAT: "Only English alphabet and spaces are allowed",
        TWO_CONSECUTIVE_SPACES: "two consecutive spaces aren't allowed"
    },
    PHONE: {
        EMPTY: "optinal phone",
        NOT_START_09: "phone number should start with 09",
        INVALID_FORMAT1: "phone number cant start with 091, 092 or 097",
        INVALID_FORMAT2: "phone number should only contains digit number",
        MAX_LENGTH: "phone number should be 10 digits",
        FINE: "fine number"
    }
};

var INPUT = {
    NAME: [
        new TextCondition(RegExp(/^.*[^a-zA-Z0-9 ].*$/), 'SYMB', false, true),
        new TextCondition(RegExp(/^.*[0-9].*$/), 'NUMBER', false, true),
        new TextCondition(RegExp(/^([a-zA-Z ]){1,2}$/), 'SHORT', false, false),
        new TextCondition(RegExp(/^[a-zA-Z ]{40,}$/), 'LONG', false, true),
    ],
    EMAIL: [
        new TextCondition(RegExp(/^$/), 'EMPTY<br/><br>\n\<br>dsdsdsdsd sdsd ssdds <br> dsdsd <br>dsdsdsds', false, false),
        new TextCondition(RegExp(/^.*[^a-z0-9\.].*$/), 'SYMB', false, true),
        new TextCondition(RegExp(/^.*\.$/), 'END_DOT', false, false),
        new TextCondition(RegExp(/^\..*$/), 'START_DOT', false, true),
        new TextCondition(RegExp(/^([a-zA-Z ]){1,2}$/), 'SHORT', false, false),
        new TextCondition(RegExp(/^.*\.\..*$/), 'TWO_DOTS', false, true),
        new TextCondition(RegExp(/^[a-zA-Z ]{40,}$/), 'LONG', false, true),
    ],
    PHONE: [
        new TextCondition(RegExp(/^[0-9]{1,9}$/), 'LENGTH', false, false),
        new TextCondition(RegExp(/^[0-9]{11,}$/), 'MAX_LENGTH', false, true),
        new TextCondition(RegExp(/^[^0].*/), 'START0', false, true),
        new TextCondition(RegExp(/^0[^9].*/), 'START09', false, true),
        new TextCondition(RegExp(/^09[^345689].*/), 'START09X', false, true),
        new TextCondition(RegExp(/^.*[^0-9].*/), 'SYMB', false, true),
    ],
    PASSWORD: [
        new PasswordCondition(RegExp(/^.*[a-z].*$/), [0, 10]),
        // new PasswordCondition(RegExp(/^.*[A-Z].*$/), [0, 1]),
        // new PasswordCondition(RegExp(/^.*[0-9].*[0-9].*$/), [0, 1]),
        // // new PasswordCondition(RegExp(/^.*[!@#$%^&*].*$/), [0, 2]),
        // new PasswordCondition(RegExp(/^.*[^a-zA-Z0-9].*$/), [0, 2]),
        // new PasswordCondition(RegExp(/^.{0,40}$/), [-20, 1]),
        // new PasswordCondition(RegExp(/^.{6,}$/), [0, 1]),
        // new PasswordCondition(RegExp(/^.{10,}$/), [0, 1]),
    ],
    NORMAL: [

    ]
};

var STRENGTH_COLOR = [
    '#ff0000', '#d02d00', '#e46700',
    '#d59100', '#d2d500', '#84c200',
    '#4bbd00', '#00d000'
];


function __setValidation(id, msg, img, shake) {
    $('#' + id + 'Img').attr('src', img);
    $('#' + id + TIPS_EXTENSION).html(msg);
    if (shake) {
        $('#' + id + 'Input').addClass('shakeElementAnimate');
        setTimeout(() => {
            $('#' + id + 'Input').removeClass('shakeElementAnimate');
        }, 600);
        setTimeout(() => {
            $('#' + id + TIPS_EXTENSION).addClass(SHOW_ERROR_CLASS);
        }, 300);
    }
}




function checkTextInput(id, type, isFinal) {
    console.log(id + ' ' + type + ' ' + isFinal);
    cleanSpaces = v => v.replace(/  +/g, ' ');
    cleanDashes = v => v.replace(/[-]/g, '');
    addDashes = v => v.slice(0, 4) + (v.length > 4 ? '-' : '') + v.slice(4, 7) + (v.length > 7 ? '-' : '') + v.slice(7);

    var fine = true;
    var v = cleanSpaces($('#' + id).val());
    if (type == 'PHONE')
        v = cleanDashes(v);
    __setValidation(id, 'FINE', correctImg, false);
    if (isFinal)
        v = v.trim();
    console.log(v);
    for (cdt of INPUT[type])
        if (!cdt.test(v)) {
            fine = false;
            __setValidation(id, cdt.flagMsg, incorrectImg, cdt.makeChange);
            if (cdt.makeChange) {
                v = cdt.getMatched(v);
                if (isFinal)
                    v = v.trim();
            }
        }

    if (type == 'PHONE')
        v = addDashes(v);
    $('#' + id).val(v);
    $('#' + id).attr('value', v);
    console.log(v);
    return fine;
}

function checkEmptyInput(id) {
    if ($('#' + id).val().length == 0) {
        __setValidation(id, 'REQUIERD', incorrectImg, true);
        return false;
    }
    return true;
}

function evaluateStrength(id) {
    v = $('#' + id).val();
    $('#' + id).attr('value', v);
    var score = 0;
    var total = 0;

    for (cdt of INPUT['PASSWORD']) {
        score += cdt.evaluate(v);
        total += cdt.getScore();
    }
    if (score < 0) {
        score = 0; // too long password
    }
    var rat = 100 * score / total;
    console.log(rat + ' ' + score + ' ' + total);
    $('#' + id + STRENGTH_BAR_EXTENSION)
        .css({
            'width': rat + '%',
            'background-color': STRENGTH_COLOR[score - 1]
        });

    return score > 4;
}

function passwordMatching(passwordId, confPasswordId) {
    var passwordStr = $('#' + passwordId).val();
    if (confPasswordId != undefined) {
        var confPasswordStr = $('#' + confPasswordId).val();
        $('#' + confPasswordId).attr('value', confPasswordStr);

        if (confPasswordStr.length == 0) {
            return false;
        }

        if (passwordStr == confPasswordStr) {
            return true;
        }
    }
    return false;
}

function checkPasswordInput(passwordId, confPasswordId) {
    var ret = true;
    if (passwordId != undefined)
        ret = evaluateStrength(passwordId);
    if (confPasswordId != undefined)
        ret = passwordMatching(passwordId, confPasswordId) && ret;
    return ret;
}

function hideErrors(id) {
    $('#' + id).on('input', e => $('#' + id + TIPS_EXTENSION).removeClass(SHOW_ERROR_CLASS));
    $('#' + id).focusout(() => $('#' + id + TIPS_EXTENSION).removeClass(SHOW_ERROR_CLASS));
}

function initializeInputValidator(id, type) {
    hideErrors(id);
    $('#' + id)
        .on('input', e => checkTextInput(e.target.id, type, false));
}

function preventQuickAction(id, submitBtnIdJs) {
    if (id != undefined)
        $('#' + id)
        .on('keydown',
            e => {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    console.log('wtf');
                    $("#" + submitBtnIdJs)
                        .click();
                }
            }
        );
}

function initializePasswordInputValidator(passwordId, confPasswordId) {
    for (id of[passwordId, confPasswordId])
        if (id != undefined) {
            hideErrors(id);
            $('#' + id).on('input', e => checkPasswordInput(passwordId, confPasswordId));
        }
}

function initializeFormValidator(submitBtnIdJs, submitBtnIdServer, fields) {

    for ([type, details] of Object.entries(fields)) {
        if (type == 'PASSWORD')
            for (passwrodIds of details) {
                preventQuickAction(passwrodIds.passwordId, submitBtnIdJs);
                preventQuickAction(passwrodIds.confPasswordId, submitBtnIdJs);
                initializePasswordInputValidator(passwrodIds.passwordId, passwrodIds.confPasswordId);
            }
        else
            for (detail of details) {
                preventQuickAction(detail.id, submitBtnIdJs);
                initializeInputValidator(detail.id, type);
            }
    }
    $("#" + submitBtnIdJs)
        .click(
            () => {
                var fine = true;
                for ([type, details] of Object.entries(fields))
                    if (type == 'PASSWORD')
                        for (passwrodIds of details) {
                            fine = checkPasswordInput(passwrodIds.passwordId, passwrodIds.confPasswordId) && fine;
                            fine = checkEmptyInput(passwrodIds.passwordId) && fine;
                            if (passwrodIds.confPasswordId != undefined)
                                fine = checkEmptyInput(passwrodIds.confPasswordId) && fine;
                        }
                    else
                        for (detail of details) {
                            fine = checkTextInput(detail.id, type, true) && fine;
                            if (detail.require)
                                fine = checkEmptyInput(detail.id) && fine;
                        }
                if (fine)
                    $('#' + submitBtnIdServer)
                    .click();
            }
        );
}

function ajaxRequest(urlPHP, _data, successFunction, errorFuntion) {
    return $.ajax({
        type: 'get',
        url: urlPHP,
        data: _data,
        async: true,
        timeout: 2000,
        success: data => successFunction($.parseJSON(data)),
        error: (xhr, ajaxOptions, thrownError) => errorFuntion(thrownError),
    });
}

function initializeInteractiveChecker(id, type, successMsg, failMsg, manipulateInput, urlPHP, indexGET) {
    successFunction = res => __setValidation(id, type + ' : ' + (res ? successMsg : failMsg), (res ? correctImg : incorrectImg), false);
    errorFuntion = exception => exception == 'timeout' ? __setValidation(id, type + ' : ' + 'bad network', incorrectImg, true) : {};
    $('#' + id).on('input',
        function(e) {
            if (checkTextInput(id, type, false)) {
                clearTimeout($.data(this, 'timerAjaxCallIntCheck'));
                if ($.data(this, 'ajaxCallIntCheck'))
                    $.data(this, 'ajaxCallIntCheck').abort();
                var _data = {};
                _data[indexGET] = manipulateInput($(this).val());
                $.data(this, 'timerAjaxCallIntCheck',
                    setTimeout(
                        () => {
                            __setValidation(id, 'LOADING', loadingImg, false);
                            $.data(this, 'ajaxCallIntCheck', ajaxRequest(urlPHP, _data, successFunction, errorFuntion));
                        },
                        1000
                    )
                );
            }
        }
    );
}