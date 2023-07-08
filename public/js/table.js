imgUrlCollapse = LocalURL + 'public/icon/collapse.svg';
imgUrlUncollapse = LocalURL + 'public/icon/uncollapse.svg';
imgUrlDestroy = LocalURL + 'public/icon/exit.svg';
imgUrlAdd = LocalURL + 'public/icon/add.svg';
imgUrlOption = LocalURL + 'public/icon/option.svg';
imgUrlSearch = LocalURL + 'public/icon/search.svg';
imgUrlSearch2 = LocalURL + 'public/icon/search2.svg';
imgUrlArrowLeft = LocalURL + 'public/icon/arrowLeftWhite.svg';
imgUrlArrowRight = LocalURL + 'public/icon/arrowRightWhite.svg';
imgUrlInfo = LocalURL + 'public/icon/hint.svg';
imgUrlDArrowsUp = LocalURL + 'public/icon/doubleArrowsUp.svg';
imgUrlDArrowsDown = LocalURL + 'public/icon/doubleArrowsDown.svg';
imgUrlDArrows = LocalURL + 'public/icon/doubleArrows.svg';
class Table {
    constructor(
        tableId,
        tableContent,
        curTotRow,
        isAdmin = true,
        hasCollapseB = true,
        hasDestroyB = true,
        hasAddB = true,
        hasSearchF = true,
        searchPercent = 5,
        searchWord = '',
        titlePercent = 50,
        parent = 'container'
    ) {
        this.tableId = tableId;
        this.tableContent = tableContent;
        this.hasCollapseB = hasCollapseB;
        this.hasDestroyB = hasDestroyB;
        this.hasAddB = hasAddB;
        this.hasSearchF = hasSearchF;
        this.searchPercent = searchPercent;
        this.searchWord = searchWord;
        this.titlePercent = titlePercent;
        this.parent = parent;
        this.isAdmin = isAdmin;
        this.curNumber = curNumber;
        this.totRow = curTotRow;
        this.wasCollapsed = false;
        this.idx = tableContent.colName.length;
        this.colCount;
        this.updateContent = [];
        this.buildTableStructure();
        this.insertData();
        this.bulidTableCaption();

        if (this.hasSearchF) {
            this.buildSearchFeild();
        }

        if (this.hasAddB) {
            this.createAddButton();
        }

        if (this.hasCollapseB) {
            this.createCollapseButton();
        }

        if (this.hasDestroyB) {
            this.createDestroyButton();
        }
    }

    buildTableStructure() {
        $('<div/>', {
                id: 'tContainer' + this.tableId,
                class: 'tableContainer'
            })
            .append(
                $('<table/>', {
                    id: this.tableId,
                    class: 'table'
                })
                .append(
                    $('<thead/>', {
                        id: 'thead' + this.tableId
                    }),

                    $('<tbody/>', {
                        id: 'tbody' + this.tableId
                    }),

                    $('<tfoot/>', {
                        id: 'tfoot' + this.tableId
                    })
                )
            )
            .appendTo('#' + this.parent);
        this.buildTable();
    }

    buildCols() {
        var colsWidth = Array();
        var me = this;
        calculateColsWidth();
        createCols();

        function calculateColsWidth() {
            var numberOfVisbleCol = me.tableContent.colName.length;
            var conMaxWidth = $('#' + me.parent).width();
            var curWidth = 0;
            var cellPadding = 10;
            var avrPXforLetter = 13;

            for (var i = 0; i < numberOfVisbleCol; i++) {
                var suitableLength = calculateOneColSutabileWidth(i);
                if (suitableLength == -1) {
                    me.idx = i;
                    continue;
                }
                var suitableWidth = ((suitableLength * avrPXforLetter) + cellPadding);
                curWidth += suitableWidth;
                if (curWidth > conMaxWidth) {
                    curWidth -= suitableWidth;
                    break;
                }
                colsWidth.push(suitableWidth);
            }

            var scalingFactor = conMaxWidth / curWidth;
            for (i in colsWidth) {
                colsWidth[i] *= scalingFactor;
            }

        }

        function calculateOneColSutabileWidth(colIndex) {
            var headerLength = me.tableContent.colName[colIndex].length;
            var sub = me.tableContent.colName[colIndex];
            sub = sub.substr(sub.length - 3);

            if (sub == '.id')
                return -1;

            var totLenght = 0;
            for (var row = 0; row < me.tableContent.data.length; row++) {
                totLenght += me.tableContent.data[row][colIndex].length;
            }

            var avrLength = Math.floor(totLenght / me.tableContent.data.length);
            var suitableLength = Math.max(avrLength, headerLength);
            return suitableLength;
        }

        function createCols() {
            $('<colgroup/>', {
                    id: 'colgroup' + me.tableId
                })
                .prependTo('#' + me.tableId);

            for (var colW of colsWidth) {
                $('<col>')
                    .css('width', colW + 'px')
                    .appendTo('#colgroup' + me.tableId);
            }

            $('<col>')
                .appendTo('#colgroup' + me.tableId)
                .css('width', 40 + 'px');
        }
        return colsWidth.length;
    }

    buildTable() {
        this.colCount = this.buildCols();
        this.buildHead();
        this.buildBody();
        this.buildFoot();
    }

    buildHead() {
        $('<tr/>', {
                id: 'theadRow' + this.tableId,
            })
            .appendTo("#thead" + this.tableId);
        this.buildHeadCols();
    }

    destroyHead() {
        $('#thead' + this.tableId)
            .empty();
    }

    buildBody() {
        for (var dataRow in this.tableContent.data) {
            this.pushRow(dataRow);
        }

    }

    destroyBody() {
        $('#tbody' + this.tableId)
            .empty();
    }

    pushRow(rowId) {
        $('<tr/>', {
                id: 'tbodyRow' + rowId + this.tableId,
            })
            .appendTo('#tbody' + this.tableId);

        for (var i = 0; i < this.colCount; i++) {
            $('<td/>', {
                    class: 'collapseCells'
                })
                .appendTo('#tbodyRow' + rowId + this.tableId);
        }
    }

    popRow(rowId) {
        $('#tbodyRow' + rowId + this.tableId)
            .remove();
    }

    buildFoot() {
        $('<tr/>', {
                id: 'tfootRow' + this.tableId
            })
            .appendTo("#tfoot" + this.tableId);
    }

    destroyFoot() {
        $('#tfoot' + this.tableId)
            .empty();
    }

    refreshFoot() {
        this.destroyFoot();
        this.buildFoot();
        this.insertDataFoot();
    }

    buildHeadCols() {
        var me = this;
        for (var i = 0; i < this.colCount; i++) {
            $('<th/>', {
                    id: 'colHeader' + this.tableId + i,
                    value: 1
                })
                .on('click', e => sortingImgFunctionailty(e))
                .append(
                    $('<span/>'),

                    $('<img>', {
                        src: imgUrlDArrows,
                        length: '15px',
                        width: '15px',
                    })
                    .css('margin-left', '3px')
                )
                .appendTo('#theadRow' + this.tableId);

            function sortingImgFunctionailty(e) {
                var ascend = (e.currentTarget.attributes.value.value != 0)
                var id = e.currentTarget.attributes.id.value;

                $('#theadRow' + me.tableId)
                    .children('th')
                    .each(
                        function(index) {
                            if ($(this).attr('id') != id) {
                                $(this)
                                    .css('background-color', '#0A2738')
                                    .attr('value', 1)
                                    .find('img')
                                    .attr('src', imgUrlDArrows);
                            } else {
                                i = index;
                                $(this)
                                    .css('background-color', (ascend) ? 'green' : 'brown');
                            }
                        }
                    );

                e.currentTarget.children[1].attributes.src.value = (ascend) ?
                    imgUrlDArrowsUp : imgUrlDArrowsDown;
                e.currentTarget.attributes.value.value ^= 1;

                var colName = me.tableContent.colName[i];
                sendQuerySort(colName, ascend, me);
            }
        }

        $('<th/>')
            .text('')
            .appendTo('#theadRow' + this.tableId);
    }

    insertDataHead() {
        var me = this;
        $('#theadRow' + this.tableId)
            .children('th')
            .each(
                function(index) {
                    if (me.idx <= index) {
                        index++;
                    }
                    $(this)
                        .find('span')
                        .text(me.tableContent.colName[index]);
                }
            );
    }

    insertDataBody() {
        var me = this;
        $('#tbody' + this.tableId)
            .children('tr')
            .each(
                function(dataRow) {
                    $(this)
                        .children('td')
                        .each(
                            function(i) {
                                if (i >= me.idx) {
                                    i++;
                                }
                                $(this)
                                    .text(me.tableContent.data[dataRow][i]);
                            }
                        );
                }
            );
        this.fixLastCol();

    }

    insertDataFoot() {
        this.buildPagesControler();
    }

    insertData() {
        this.replaceContent(true);
    }

    setTableContent(tableContent) {
        setTimeout(() => {
            this.tableContent = tableContent;
            this.destroyBody();
            this.buildBody();
            this.destroyFoot();
            this.buildFoot();
            this.replaceContent(false);
            setTimeout(() => {
                this.unloading();
            }, 500);

        }, 1000);
    }

    replaceContent(withHead) {
        if (withHead)
            this.insertDataHead();
        this.insertDataBody();
        this.insertDataFoot();
        if (withHead)
            this.unloading();
    }

    bulidTableCaption() {
        $('<caption/>', {
                id: 'tCaption' + this.tableId
            })
            .text(tableContent.tableName)
            .prependTo('#' + this.tableId);

        posDivByDiv(this.titlePercent, '#tCaption' + this.tableId, '#' + this.tableId);
    }

    fixLastCol() {
        for (var row = 0; row < this.tableContent.data.length; row++) {
            this.createOptionButton(row);
        }
    }

    createOptionButton(row) {
        var me = this;
        $('<td/>', {
                id: 'optionButton' + row + this.tableId,
                class: 'destroying collapseCells',
                value: this.tableId + 'r' + row
            })
            .on('click', function(e) {
                me.openOption(e);
            })
            .appendTo('#tbodyRow' + row + this.tableId);

        $('<div/>', {
                id: 'relInsideTd' + row + this.tableId,
                class: 'relInsideTd destroying'
            })
            .append(
                $('<img>', {
                    src: imgUrlOption,
                    class: 'destroying'
                })
                .css('display', 'none')
            )
            .appendTo('#optionButton' + row + this.tableId);
    }

    openOption(e) {
        var me = this;
        var value = e.currentTarget.attributes.value.value;
        var tableId = value.slice(0, value.lastIndexOf('r'));
        var row = value.slice(value.lastIndexOf('r') + 1);

        var deleteB =
            (me.isAdmin) ?
            $('<div/>', {
                id: 'delete' + row + tableId,
                class: 'option'
            })
            .text('Delete')
            .on('click', () => askToDelete(row)) : null;

        if ($('#subMenu' + row + tableId).length) {
            $('#subMenu' + row + tableId)
                .remove();
        } else {
            $('<div/>', {
                    id: 'subMenu' + row + tableId,
                    class: 'subMenu'
                })
                .append(
                    $('<div/>', {
                        id: 'optionContainer' + row + tableId,
                        class: 'optionContainer'
                    })
                    .append(
                        $('<div/>', {
                            id: 'showFullDetails' + row + tableId,
                            class: 'option'
                        })
                        .text('Show Full Details')
                        .on('click', () => showDetails(row)),

                        deleteB
                    )
                )
                .appendTo('#relInsideTd' + row + tableId);
        }

        function showDetails(row) {
            var id = me.tableContent.data[row][me.idx];

            function confirmUpdate() {
                sendQueryUpdate(id, me);
            }

            buildPopup(true, confirmUpdate, true);
            sendQueryDetails(id, me);
        }


        function askToDelete(row) {
            var id = me.tableContent.data[row][me.idx];
            var content = ['are you sure you want to delete??'];

            function tryToDelete() {
                sendQueryDelete(id, me, row);
            }

            buildPopup(true, tryToDelete);
            $('#popupContainer')
                .css('height', '20%');
            insertContent(content);
        }

    }

    deleteRow(row) {
        console.log(row);
        $('#tbodyRow' + row + this.tableId)
            .fadeOut(1000);

    }

    createCollapseButton() {
        $('<div/>', {
                id: 'tCollapseButton' + this.tableId,
                class: 'collapse topOption',
            })
            .click(
                //make the collapse button work
                () => {
                    $('#collapseIcon' + this.tableId)
                        .attr('src', function(index, src) {
                            return (src == imgUrlCollapse) ?
                                imgUrlUncollapse : imgUrlCollapse;
                        });
                    this.wasCollapsed = !this.wasCollapsed;
                    this.collapseTableBody();
                }
            )
            .append(
                //create and get image of collapse button
                $('<img>', {
                    id: 'collapseIcon' + this.tableId,
                    src: imgUrlCollapse
                })
            )
            .appendTo('#tContainer' + this.tableId);

    };

    createAddButton() {
        $('<div/>', {
                id: 'tAddButton' + this.tableId,
                class: 'add topOption'
            })
            .click(
                () => {
                    if (this.tableContent.tableName != 'Users') {
                        buildPopup(true, addNewRow);
                        sendQueryDetails();
                    } else {
                        window.location.href = LocalURL + 'user/signup/';
                    }
                }
            )
            .append(
                //create and get image of collapse button
                $('<img>', {
                    src: imgUrlAdd
                })
            )
            .appendTo('#tContainer' + this.tableId);
    }

    createDestroyButton() {
        $('<div/>', {
                id: 'tDestroyButton' + this.tableId,
                class: 'destroy topOption',
            })
            .append(
                //create and get image of collapse button
                $('<img>', {
                    src: imgUrlDestroy
                })
            )
            .click(() => this.destroyTable(true))
            .appendTo('#tContainer' + this.tableId);
    }


    buildSearchFeild() {
        var me = this;
        $('<form/>', {
                id: 'tSearchForm' + this.tableId
            })
            .css('left', this.searchPercent + '%')
            .append(
                $('<div/>', {
                    id: 'tSearchInput' + this.tableId,
                    class: 'textInput searchField',
                }).append(
                    $('<input/>', {
                        id: 'tSearch' + this.tableId,
                        type: 'text',
                        name: 'tableSearch',
                        value: this.searchWord
                    })
                    .val(this.searchWord)
                    .on('keydown',
                        function(e) {
                            if (e.keyCode == 13) {
                                e.preventDefault();
                                sendQuerySearch($(this).val(), me);
                            }
                        }
                    ),

                    $('<label/>', {
                        for: 'tSearch' + this.tableId
                    })
                    .text('Search'),
                    $('<div/>')
                    .css('postion', 'relative')
                    .append(
                        $('<img>', {
                            id: 'searchIcon' + this.tableId,
                            src: imgUrlSearch
                        })
                        .mouseenter(() => $('#searchIcon' + this.tableId)
                            .attr('src', imgUrlSearch2)
                        )
                        .mouseleave(
                            () => $('#searchIcon' + this.tableId)
                            .attr('src', imgUrlSearch)
                        )
                        .on('click',
                            () => {
                                sendQuerySearch(
                                    $('#tSearch' + this.tableId).val(),
                                    me
                                );
                            }
                        )
                    )
                )
            )
            .prependTo('#tContainer' + this.tableId);

        initializeInputValidator('tSearch' + this.tableId, 'NORMAL');
    }

    buildPagesControler() {
        var totRow = this.totRow;
        var me = this;
        buildFooter();
        AllRowsCounts();
        buildPagesMover();
        rowPerPage();

        function buildFooter() {
            $('<td/>', {
                    id: 'tfootCell' + me.tableId,
                    colspan: (me.colCount + 1)
                })
                .append(
                    $('<div/>', {
                        id: 'pagesControler' + me.tableId,
                        class: 'rowWithSpaceBetween'
                    })
                )
                .appendTo('#tfootRow' + me.tableId);
        }

        function AllRowsCounts() {
            $('<div/>', {
                    id: 'rowCounterContainer' + me.tableId,
                    class: 'rowCountContainer'
                })
                .append('from <i>' + from + '</i>' +
                    ' to <i>' + to + '</i>' +
                    ' of <i>' + allRow + '</i> entries')
                .appendTo('#pagesControler' + me.tableId);
        }

        function buildPagesMover() {
            $('<div/>', {
                    id: 'pagesMoverContainer' + me.tableId,
                    class: 'rowContainer'
                })
                .append(
                    $('<div/>', {
                        id: 'left' + me.tableId
                    })
                    .on('click',
                        () => {
                            sendQueryPage(
                                totRow,
                                (me.curNumber != 1) ? me.curNumber - 1 : 1,
                                me
                            );
                        }
                    )
                    .append(
                        $('<img>', {
                            src: imgUrlArrowLeft
                        })
                    ),

                    $('<div/>', {
                        id: 'numberSection' + me.tableId,
                        class: 'rowContainer'
                    })
                    .css({
                        'margin': '15px',
                        'font-size': '22pt'
                    }),

                    $('<div/>', {
                        id: 'right' + me.tableId
                    })
                    .on('click',
                        () => {
                            sendQueryPage(
                                totRow,
                                (me.curNumber + 1 <= Math.ceil(allRow / totRow)) ?
                                me.curNumber + 1 : me.curNumber,
                                me
                            );
                        }
                    )
                    .append(
                        $('<img>', {
                            src: imgUrlArrowRight
                        })
                    )
                )
                .appendTo('#pagesControler' + me.tableId);

            var numberSectionLength = 5;
            var boundry = Math.floor(numberSectionLength / 2);
            var numbers = Array();
            for (var value = -boundry; value <= boundry; value++) {
                numbers.push(value);
            }

            $('#numberSection' + me.tableId)
                .ready(
                    () => {
                        for (var index of numbers) {
                            $('<div/>', {
                                    id: 'CurContainer' + (index + 2) + me.tableId,
                                    class: 'numberContainer'
                                })
                                .text(
                                    (me.curNumber + index > 0 &&
                                        me.curNumber + index <= Math.ceil(allRow / totRow)) ?
                                    me.curNumber + index : ''
                                )
                                .css({
                                    'font-weight':
                                        (index == 0) ? 'bold' : 'normal'
                                })
                                .on('click',
                                    function(e) {
                                        var id = e.currentTarget.attributes.id.value;
                                        sendQueryPage(
                                            totRow,
                                            parseInt($('#' + id).text()),
                                            me
                                        );
                                    }
                                )
                                .appendTo('#numberSection' + me.tableId);
                        }

                        $('.numberContainer')
                            .each(
                                function() {
                                    if ($(this).text() == '') {
                                        $(this).removeClass('numberContainer');
                                    }
                                }
                            );
                    }
                )
        }

        function rowPerPage() {
            $('<div/>', {
                    id: 'rowCountDeterminerContainer' + me.tableId,
                    class: 'rowContainer'
                })
                .append(
                    $('<div/>', {
                        id: 'lableForSelect' + me.tableId
                    })
                    .text('rows per page:'),

                    $('<select/>', {
                        id: 'selectNOPages' + me.tableId,
                        class: 'selctor',
                    })
                    .on('change',
                        () => {
                            var totRow = parseInt(
                                $('#selectNOPages' + me.tableId)
                                .val()
                            );
                            sendQueryPage(totRow, me.curNumber, me);
                        }
                    )
                    .append(
                        $('<option/>', {
                            value: 10,
                        })
                        .text(10),

                        $('<option/>', {
                            value: 20,
                        })
                        .text(20),

                        $('<option/>', {
                            value: 50,
                        })
                        .text(50)
                    )
                )
                .appendTo('#pagesControler' + me.tableId);

            $('#selectNOPages' + me.tableId +
                    ' option[value="' + me.totRow + '"]')
                .attr("selected", true);
        }
    }

    loading() {
        if (!this.wasCollapsed)
            this.collapseTableBody();
        this.refreshFoot();
        this.clickable(false);
    }

    refresh() {
        this.refreshFoot();
    }

    unloading() {
        if (!this.wasCollapsed)
            this.collapseTableBody();
        this.clickable(true);
    }

    clickable(isClickable) {
        if (!isClickable) {
            if ($('#notClickable' + this.tableId).length == 0) {
                $('<div/>', {
                        id: 'notClickable' + this.tableId,
                        class: 'notClickable'
                    })
                    .height(
                        $('#' + this.tableId).height()
                    )
                    .width(
                        $('#' + this.tableId).width()
                    )
                    .appendTo('#tContainer' + this.tableId);
            }
        } else {
            if ($('#notClickable' + this.tableId).length > 0) {
                $('#notClickable' + this.tableId)
                    .remove();
            }
        }
    }

    collapseTableBody() {
        var me = this;
        $('#tbody' + this.tableId)
            .find('tr')
            .each(
                function() {
                    me.collapseRow($(this).attr('id'), true);
                }
            );
    }

    collapseRow(rowId, animation) {
        var time = (animation) ? 500 : 0;
        $('#' + rowId)
            .find('td')
            .toggleClass('collapseCells')
            .find('img')
            .stop(true)
            .animate({
                height: "toggle",
                width: "toggle"
            }, time);
    }

    checkTop() {
        if ($('#tbody' + this.tableId)
            .find('td')
            .hasClass('collapseCells')) {
            return true;
        }
        return false;
    }

    destroyTable(animation) {
        var closingVDuration = 500;
        var closingHDuration;
        if (!animation) {
            closingHDuration = 0;
            closingVDuration = 0;
        } else {
            if (this.checkTop()) {
                closingVDuration = 0;
            }
            closingHDuration = 500;
        }
        if (!this.checkTop()) {
            this.collapseTableBody();
        }

        setTimeout(
            () => {
                $('#tContainer' + this.tableId)
                    .animate({
                        'width': '0%',
                        'opacity': 0,
                        'overflow': 'hidden'
                    }, closingHDuration);

                $('#thead' + this.tableId + ',' + '#tCaption' + this.tableId)
                    .find('th')
                    .css({
                        'transition': '0.5s',
                        'font-family': 'initial !important'
                    })
                    .toggleClass('collapseCells');

                $('#tContainer' + this.tableId)
                    .find('img')
                    .css({
                        'height': 0,
                        'width': 0
                    });

                setTimeout(
                    () => {
                        $('#tContainer' + this.tableId)
                            .css({
                                'display': 'none'
                            });

                        $('#tContainer' + this.tableId)
                            .remove();
                    },
                    closingHDuration
                );

            },
            closingVDuration
        );
    }
}