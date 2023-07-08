function initializeAutocomplete(input, allSugg, limit) {
    var autocomp = input.parentNode.getElementsByClassName('autocomplete')[0];
    var textInput = input.getElementsByTagName('input')[0];
    var imgInput = input.getElementsByTagName('img')[0];
    var list;
    var currIdx = -1;
    var etc = '........';

    function buildList(sugg) {
        list = document.createElement('div');
        list.setAttribute("class", "autocompleteList");
        for (var i = 0; i < sugg.length; i++) {
            var line = document.createElement('div');
            line.classList.add('autocompleteLineBackg' + (i % 2));
            line.innerHTML = sugg[i];
            var newinp = document.createElement('input');
            newinp.setAttribute('type', 'hidden');
            newinp.setAttribute('value', sugg[i]);
            line.appendChild(newinp);

            if (sugg[i] != etc) {
                line.addEventListener('click', function() {
                    textInput.value = this.getElementsByTagName('input')[0].value;
                    // textInput.value = this.innerHTML;
                    updateImg();
                    close();
                });
            }
            list.appendChild(line);
        }
        autocomp.appendChild(list);
    }

    function moveFocus(delta) {
        deactivate();
        currIdx += delta;
        var max = list.getElementsByTagName('div').length - 2;
        if (list.getElementsByTagName('div').length < 5) {
            max++;
        }
        if (currIdx < 0) {
            currIdx = max;
        }
        if (currIdx > max) {
            currIdx = 0;
        }
        activate();
    }

    function activate() {
        list.getElementsByTagName('div')[currIdx].classList.add('autocompleteActiveLine');
    }

    function deactivate() {
        if (currIdx != -1) {
            list.getElementsByTagName('div')[currIdx].classList.remove('autocompleteActiveLine');
        }
    }

    function close() {
        currIdx = -2;
        autocomp.innerHTML = "";
    }

    function updateImg() {
        var val = textInput.value;
        if (val == undefined) {
            val = "";
        }
        textInput.setAttribute('value', val);
        if (allSugg.includes(val)) {
            imgInput.setAttribute('src', './public/icon/checked.svg');
        } else {
            imgInput.setAttribute('src', './public/icon/unchecked.svg');
        }
    }

    function open() {
        close();
        var val = textInput.value.toLowerCase();
        if (val == undefined) {
            val = "";
        }
        updateImg();
        var sugg = [];
        currIdx = -1;
        for (oneSugg of allSugg) {
            for (var i = 0; i < oneSugg.length; i++) {
                if (oneSugg.substr(i, val.length).toLowerCase() == val) {
                    sugg.push(oneSugg);
                    break;
                }
            }
            if (sugg.length == limit) {
                sugg.pop();
                sugg.push(etc);
                break;
            }
        }
        if (sugg.length > 0) {
            buildList(sugg);
        }
    }
    textInput.addEventListener('input', open);
    textInput.addEventListener('keydown', function(e) {
        if (e.keyCode == 40) {
            moveFocus(1);
        }
        if (e.keyCode == 38) {
            moveFocus(-1);
        }
        if (e.keyCode == 13) {
            if (currIdx != -2) {
                e.preventDefault();
                list.getElementsByTagName('div')[currIdx].click();
            }
        }
        if (e.keyCode == 27) {
            close();
        }
    });
    input.parentNode.addEventListener('focusin', open);
    input.parentNode.addEventListener('focusout', () => {
        setTimeout(() => {
            close();
        }, 300);
    });


}