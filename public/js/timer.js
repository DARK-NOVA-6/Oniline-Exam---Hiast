function startTimer() {
    $("#totques").text(totques);
    $("#nQuesLeft").text(nQuesLeft);
    $("#currQues").text(currQues);

    var START = new Date(startDate).getTime();
    var delta = duration * 60000 + 1;
    var END = START + delta;
    timer = setInterval(
        () => {
            var diff = Math.floor(
                (END - new Date().getTime()) / 1000
            );
            if (diff < 0) {
                clearInterval(timer);
                window.alert("Test Ended");
                diff = 0;
            }
            var h = Math.floor(diff / (60 * 60));
            var m = Math.floor(diff / 60) % 60;
            var s = diff % 60;

            function getChar(x) {
                if (x >= 10) {
                    return x;
                } else {
                    return "0" + x;
                }
            }
            $("#timerBar").text(
                getChar(h) + ":" +
                getChar(m) + ":" +
                getChar(s)
            );
        },
        1
    );
    if (examFinished) {
        $("#timerBar").text(fTime);
        doIfExamFinished();
    }
}

function doIfExamFinished() {
    clearInterval(timer);
    $('#submitB').css('background-color', 'grey');
    $('#submitB').text(fullMark + '%');
}

function askToEnd() {
    if (!examFinished) {
        buildPopup(true, finishExam);
        insertContent(['are you sure you want to SUBMIT ?'])
    }
}

function finishExam() {
    examFinished = true;
    doIfExamFinished();
    setCookie('examFinished', true);
    setCookie('fTime', $('#timerBar').text());
    buildQuestion();
}

$(document)
    .ready(
        () => {
            $(".footer")
                .css('margin-left', (-121) + 'px');
        }
    )