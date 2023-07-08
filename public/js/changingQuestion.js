function changeQues(val) {
    if (val >= 1 && val <= totques) {
        currQues = val;
        $("#currQues")
            .text(currQues);
    }
    setCookie('curQ', currQues);
    buildList();
    buildQuestion();
}

function buildList() {
    $("#list")
        .empty();
    start = Math.max(1, currQues - 2);
    end = Math.min(totques, currQues + 2);
    if (end - start + 1 < 5) {
        if (start == 1) {
            end = Math.min(5, totques);
        } else {
            start = Math.max(1, totques - 5);
        }
    }
    for (i = start; i <= end; i++) {
        $("#list")
            .append(
                $('<div/>', {
                    id: i,
                    class: 'pageNumber'
                })
                .on('click',
                    e => {
                        var x = parseInt(e.currentTarget.id);
                        if (x != currQues)
                            changeQues(x);
                    }
                )
                .append(
                    (i == currQues) ?
                    $('<b/>')
                    .append(i) :
                    $('<span/>')
                    .append(i)
                )
            );
    }

    function hide(id) {
        $('#' + id)
            .attr("hidden", '');
    }

    function unhide(id) {
        $('#' + id)
            .removeAttr("hidden");
    }

    if (currQues == 1) {
        hide("prev");
        hide("first");
    } else {
        unhide("prev");
        unhide("first");
    }
    if (currQues == totques) {
        hide("last");
        hide("next");
    } else {
        unhide("last");
        unhide("next");
    }
}

function buildQuestion() {
    if (testing === undefined) {
        testing = false;
    }
    if (examFinished) {
        $('#quesMark')
            .text(correctAnswer[currQues].mark +
                '/' + correctAnswer[currQues].fMark);
    }
    disableOp = false;
    if (testing | examFinished)
        disableOp = true;
    console.log(disableOp);
    $('#quesNumb')
        .text(currQues + ': ' + questions[currQues].title);
    $('#textQuesSec p')
        .empty();
    $('#textQuesSec p')
        .append(questions[currQues].text);
    $('#optionsSec')
        .empty();
    for (i = 1; i < questions[currQues].options.length; i++) {
        $('#optionsSec')
            .append(
                $('<div/>', {
                    class: 'radWthLab'
                })
                .append(
                    $('<label/>', {
                        class: 'choice',
                        for: 'opt' + questions[currQues].options[i].order
                    })
                    .text(questions[currQues].options[i].text)
                    .append(
                        $('<input/>', {
                            type: 'checkbox',
                            disabled: disableOp,
                            value: 'opt' + questions[currQues].options[i].order,
                            id: 'opt' + questions[currQues].options[i].order
                        })
                        .prop('checked', (questions[currQues].options[i].isSelected)),

                        $('<span/>', {
                            class: testing ?
                                'checkmark' : examFinished ?
                                (
                                    correctAnswer[currQues].correctAn
                                    .includes(questions[currQues].options[i].order) ?
                                    'checkmark passed' : 'checkmark wrong'
                                ) : 'checkmark'
                        })
                    ),

                    $('<br/>')
                )
            );
    }
}



function setCookie(cname, cvalue) {
    document.cookie = cname + "=" + cvalue + ";";
}