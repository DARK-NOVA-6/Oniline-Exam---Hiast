function buildPopup(isConifrm, onConfirm = doNothing, isFake = false) {
    $('<div/>', {
            id: 'popup',
            class: 'popupBackground'
        })
        .append(
            $('<div/>', {
                id: 'popupContainer',
                class: 'popupContainer'
            })
            .append(
                $('<div/>', {
                    id: 'topContainer',
                    class: 'rowContainer'
                })
                .css('height', '10%'),

                $('<div/>', {
                    id: 'midContainer',
                    class: 'midContainer'
                })
                .append(
                    $('<div/>', {
                        id: 'contentContainer',
                        class: 'contentContainer'
                    })
                ),

                $('<div/>', {
                    id: 'botContainer',
                    class: 'botContainer'
                })
                .append(
                    $('<div/>', {
                        id: 'exit',
                        class: 'button'
                    })
                    .on('click',
                        () => {
                            $('#popup')
                                .fadeOut(500,
                                    () => {
                                        $(this).remove();
                                    }
                                );
                        }
                    )
                    .text('cancel')
                )
            )
        )
        .prependTo('body');

    if (isConifrm) {
        insertConfirm();
    }

    function insertConfirm() {
        $('#botContainer')
            .prepend(
                $('<div/>', {
                    id: 'ok',
                    class: 'button'
                })
                .text('Confirm')
                .on('click',
                    () => {
                        if (!isFake) {
                            $('#popup')
                                .fadeOut(500,
                                    () => {
                                        onConfirm();
                                        $(this).remove();
                                    }
                                );
                        }
                    }
                ),

                $('<input/>', {
                    id: 'fakeOk',
                })
                .prop('hidden', true)
                .on('click',
                    () => {
                        onConfirm();
                    }
                )
            );

    }
}

function doNothing() {

}

function insertContent(content) {
    for (c in content) {
        $('#contentContainer')
            .addClass('textOnly')
            .append(
                content[c]
            );
    }
}

class TextInput {
    constructor(
        name,
        type,
        value,
        isEditable,
        depth,
        isTextInput,
        isYesNo,
        isCorrect,
        id
    ) {
        if (depth === undefined)
            depth = 0;
        isEditable = !isEditable;
        this.id = (id === undefined) ? name.replace(/ +/g, '') : id;
        this.name = name;
        this.type = type;
        if (isTextInput) {
            var textInput =
                $('<div/>', {
                    id: this.id + 'Input',
                    class: 'textAI textInput',
                })
                .append(
                    $('<input/>', {
                        id: this.id,
                        readonly: isEditable,
                        type: (type != 'PASSWORD') ? 'text' : 'password',
                        name: this.id,
                        value: value,
                    })
                    .val(value),

                    $('<label/>', {
                        for: this.id
                    })
                    .css('color', 'black'),

                    $('<div/>')
                    .css('position', 'relative')
                    .append(
                        $('<img>', {
                            id: this.id + 'Img',
                            src: imgUrlInfo
                        }),

                        $('<div/>', {
                            id: this.id + 'Tips',
                            class: 'tips'
                        })
                        .text('enter your ' + name)
                    ),
                );
        } else {
            var textInput = $('<div/>', {
                    id: this.id + 'Input',
                    class: 'inputContainer'
                })
                .append(
                    $('<label/>', {
                        for: this.id
                    })
                    .css({
                        'display': 'flex',
                        'justify-content': 'space-between',
                        'color': 'black'
                    })
                    .append(
                        $('<div/>', {

                        })
                        .text(name),

                        (!isYesNo) ? null :
                        $('<label/>', {
                            class: 'choice',
                            for: this.id + 'cBox'
                        })
                        .append(
                            $('<input/>', {
                                type: 'checkbox',
                                disabled: false,
                                id: this.id + 'cBox',
                                name: this.id
                            })
                            .prop('checked', isCorrect),

                            $('<span/>', {
                                class: 'checkmark'
                            })
                        )
                        .css('margin-right', '30px')
                    ),

                    $('<div/>', {
                        id: this.id + 'Input',
                        class: 'textAI textInput',
                    }).css({
                        'margin': '5px'
                    })
                    .append(
                        $('<textarea/>', {
                            id: this.id,
                            readonly: isEditable,
                            name: this.id,
                            spellcheck: 'false',
                            value: value,
                            rows: 1,
                            cols: 35 - (5 * depth),
                            class: 'textArea',
                            placeholder: 'enter ' + name + ' here...',
                        })
                        .css({
                            'height': (this.scrollHeight) + 'px',
                            // 'margin': '10px'
                        })
                        .val(value)
                        .on('input',
                            (e) => {
                                var id = e.target.id;
                                $('#' + id)
                                    .css('height', 'auto');

                                $('#' + id)
                                    .css({
                                        'height': $('#' + id)
                                            .prop('scrollHeight') + "px"
                                    });
                            }
                        ),

                        $('<div/>')
                        .css('position', 'relative')
                        .append(
                            $('<img>', {
                                id: this.id + 'Img',
                                src: imgUrlInfo
                            }),

                            $('<div/>', {
                                id: this.id + 'Tips',
                                class: 'tips'
                            })
                            .text('enter your ' + name)
                        ),
                    ),
                );
        }
        this.textInput = textInput;
        // this.returnMe();
        // return textInput;
    }
    returnMe() {
        return this.textInput;
    }
}
$('header').data('id', null);
class Arr {
    constructor(
        name,
        arrx,
        depth,
        isTextInput,
        isYesNo,
        isAdd,
        isDelete,
        isEdit,
    ) {
        if (depth === undefined)
            depth = 0;
        this.id = name.replace(/ +/g, '');
        this.name = name;
        this.arrx = arrx;
        var arr = $('<div/>', {
                id: this.id + 'arr',
                class: 'arrText',
            })
            .css('width', (isTextInput) ? '275px' : '430.4px')
            .append(
                $('<div/>', {
                    id: 'topSection' + this.id,
                    class: 'rowWithSpaceBetween topSection'
                })
                .append(
                    $('<div/>')
                    .append("&ensp; " + name),

                    $('<div/>')
                    .append(
                        (!isAdd) ? null :

                        $('<img/>', {
                            src: LocalURL + 'public/icon/add.svg'
                        })
                    )
                    .on('click',
                        () => {
                            if (hasPre('7zf')) {
                                buildCell(
                                    name, 'new', '', depth + 1,
                                    isTextInput, isYesNo, false,
                                    isEdit, isDelete
                                );
                            }
                        }
                    ),
                ),

                $('<div/>', {
                    id: 'botSection' + this.id,
                    class: 'botSection'
                })
                .css('padding-left', '100px')

            );
        setTimeout(
            () => {
                for (var a of arrx) {
                    if (a.isCorrect === undefined) {
                        a.isCorrect = false;
                    }
                    buildCell(
                        name, a.id, a.value,
                        depth + 1, isTextInput,
                        isYesNo, a.isCorrect,
                        isEdit, isDelete
                    );
                }
            },
            1
        );
        this.arr = arr;
    }
    returnMe() {
        return this.arr;
    }
}

function buildCell(
    name,
    id,
    value,
    depth,
    isTextInput,
    isYesNo,
    isCorrect,
    isEdit,
    isDelete,
) {
    if ($('header').data('noc') == null)
        $('header').data('noc', 0);

    else $('header')
        .data('noc', $('header').data('noc') + 1);
    botId = name.replace(/ +/g, '');
    noc = $('header').data('noc');
    $('#botSection' + botId)
        .append(
            $('<div/>', {
                id: 'de' + noc,
                class: 'rowWithSpaceBetween arxParent'
            })
            .append(
                $('<div/>')
                .css('position', 'relative')
                .append(
                    new TextInput(
                        name + ' ' + noc + '', 'text', value,
                        isEdit, depth, isTextInput,
                        isYesNo, isCorrect, id
                    )
                    .returnMe(),

                    $('<div/>', {
                        id: 'da' + noc
                    })
                    .append(
                        (!isDelete) ? null :

                        $('<img/>', {
                            src: LocalURL + 'public/icon/exit.svg',
                            class: 'destroyCell'
                        })
                    )
                    .on('click',
                        e => {
                            x = e.currentTarget.id;
                            x = x.slice(2);
                            $('#de' + x)
                                .remove();
                        }
                    ),
                )
            )
        );
}


function sendQueryPage(totRow, curNumber, me, force) {
    sAction = (data, me) => successResponse(data, me);
    if (curNumber != me.curNumber || totRow != me.totRow || force) {
        if (totRow != me.totRow)
            curNumber = 1;
        me.curNumber = curNumber;
        me.totRow = totRow;
        tTitle = me.tableContent.tableName;
        me.loading();
        options = {};
        options[tTitle + '_table'] = "page=" + curNumber + '&size=' + totRow
        sendQueryAjax(options, me, sAction);
    } else {
        console.log('same');
    }
}

function sendQuerySearch(word, me) {
    sAction = (data, me) => successResponse(data, me);
    if (word != me.searchWord) {
        me.searchWord = word;
        tTitle = me.tableContent.tableName;
        me.loading();
        options = {};
        options[tTitle + '_table'] = "search_for=" + word + "&page=1"
        sendQueryAjax(options, me, sAction);
    } else {
        console.log('same');
    }
}

function sendQuerySort(colName, asc, me) {
    sAction = (data, me) => successResponse(data, me);
    tTitle = me.tableContent.tableName;
    me.loading();
    options = {};
    options[tTitle + '_table'] = "page=1&sort_by=" + colName +
        "&sort_asc=" + asc;
    sendQueryAjax(options, me, sAction);
}

function sendQueryDetails(id, me) {
    sAction = (data, me) => fetchDetails(data, me);
    tTitle = me.tableContent.tableName;
    // me.loading();
    options = {};
    options[tTitle + '_row_query'] = "more_details=" + id;
    sendQueryAjax(options, me, sAction);
}

function sendQueryUpdate(id, me) {
    updateContent = me.updateContent;
    sAction = (data, me) => askForUpdate(data, me);
    tTitle = me.tableContent.tableName;
    // me.loading();
    formatedOption = getFormatedOption(updateContent);
    newData = {
        id: id,
        data: formatedOption
    }
    console.log(formatedOption);
    options = {};
    options[tTitle + '_row_query'] = "update=" + newData;
    // sendQueryAjax(options, me, sAction);
}

function sendQueryDelete(id, me, row) {
    $('body').data('deletedId', id);
    sAction = (data, me) => afterDelete(data, me);
    tTitle = me.tableContent.tableName;
    // me.loading();
    options = {};
    options[tTitle + '_row_query'] = "delete=" + id;
    sendQueryAjax(options, me, sAction);
}

function sendQueryAjax(options, me, sAction) {
    $.ajax({
        data: options,
        type: 'post',
        url: LocalURL + 'auto.php',
        async: true,
        timeout: 2000,
        success: function(data) {
            data = $.parseJSON(data);
            sAction(data, me);
        },
    });
}

function successResponse(data, me) {
    // console.log(data);
    tableContent.colName = data.columns;
    tableContent.data = data.data;
    curTotRow = data.size;
    me.curNumber = data.page;
    allRow = data.count;
    from = data.from;
    to = data.to;
    me.setTableContent(tableContent);
}

function fetchDetails(data, me) {
    console.log(me);
    updateRowQ(data.tx, data.arx, me)
}

function afterDelete(data, me) {
    // console.log(data);
    if (data.result) {
        id = $('body').data('deletedId');

        for (row in me.tableContent.data) {
            if (me.tableContent.data[row][me.idx] == id) {
                me.deleteRow(row);
            }
        }

        setTimeout(() => sendQueryPage(me.totRow, me.curNumber, me, true), 1500);
    } else {
        data.msg = (data.msg == undefined) ? "this item can't be deleted" : data.msg;
        buildPopup(false);
        $('#popupContainer')
            .css('height', '20%')
        insertContent([data.msg]);
    }
}

function askForUpdate(data, me) {
    if (data.result) {
        $('#popup')
            .fadeOut(500,
                () => {
                    $(this).remove();
                }
            );
        data.msg = (data.msg == undefined) ? "this item is Updated" : data.msg;
        buildPopup(false);
        $('#popupContainer')
            .css('height', '20%')
        insertContent([data.msg]);
        console.log(data.msg);
        sendQueryPage(me.totRow, me.curNumber, me, true);
    } else {
        data.msg = (data.msg == undefined) ? "this item can't be Modified" : data.msg;
        console.log(data.msg);
        // buildPopup(false);
        // $('#popupContainer')
        //     .css('height', '20%')
        // insertContent([data.msg]);
    }
}



function getFormatedOption(updateContent) {
    var tmp = {
        tx: [],
        arx: []

    };
    updateContent.forEach(
        e => {
            var name = e.name;
            if (e instanceof TextInput) {
                var value;
                value = $('#' + e.id).val();
                tmp.tx.push({
                    name: name,
                    value: value
                });
            } else if (e instanceof Arr) {
                var pid = e.id;
                var options = [];
                $('#botSection' + pid)
                    .find('textArea')
                    .each(
                        function(index) {
                            var id = $(this).attr('id');
                            var value = $(this).val();
                            var isSelected =
                                $('#' + id + 'cBox').prop('checked');
                            if (isSelected === undefined)
                                isSelected = false;
                            options.push({
                                id: id,
                                value: value,
                                isSelected: isSelected
                            })
                        }
                    )
                tmp.arx.push({
                    name: name,
                    options: options
                });
            }

        }
    );
    return tmp;
}