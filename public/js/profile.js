imgUrlInfo = LocalURL + 'public/icon/hint.svg';

function buildProfile(items) {
    for (i = 0; i < 3; i++) {
        $('<ul></ul>', {
                id: 'column' + i,
                class: 'column'
            })
            .appendTo('#marginContainer');
    }
    fillCol(items);

}

function fillCol(items) {
    for (i = 0; i < 3; i++) {
        for (j in items[i]) {
            $('#column' + i).append(
                items[i][j]
            );
        }
    }
}



class TextInput {
    constructor(name, type) {
        this.id = name.replace(/ +/g, '');
        var textInput = $('<div/>', {
                id: this.id + 'Input',
                class: 'textInput',
            })
            .append(
                $('<input/>', {
                    id: this.id,
                    type: (type != 'PASSWORD') ? 'text' : 'password',
                    name: this.id,
                    value: ''
                }),

                $('<label/>', {
                    for: this.id
                })
                .text(name),

                $('<img>', {
                    id: this.id + 'Img',
                    src: imgUrlInfo
                }),

                $('<div/>', {
                    id: this.id + 'Tips',
                    class: 'tips'
                })
                .text('enter your ' + name)
            );
        if (type != 'TEXT' && type != 'PHOTO') {
            if (type != 'PASSWORD')
                formFields[type].push({ id: this.id, require: true });
            else
                formFields[type].push({ passwordId: this.id });
        }
        return textInput;
    }
}


class ProgPar {
    constructor() {
        return $('<div/>', {
                id: 'prog'
            })
            .css('margin-top', (-5) + 'px')
            .append(
                $('<div/>', {
                    id: "passwordProgBar",
                    role: 'progressbar',
                    'aria-valuenow': "40",
                    'aria-valuemax': "100",
                    'aria-valuemin': "0",
                })
            );
    }
}



personImg = LocalURL + 'public/icon/person.svg';

class Photo {
    constructor(name, type) {
        this.id = name.replace(/ +/g, '');
        return $('<div/>', {
                id: this.id,
                class: 'photoController bgColor'
            })
            .css({
                'width': 290 + 'px',
                'height': 290 + 'px'
            })
            .append(
                $('<div/>', {
                    id: 'photoButton',
                    class: 'row'
                })
                .append(
                    $('<label/>', {
                        id: 'photoSelectButton' + this.id,
                        class: 'button photoButton',
                        for: 'photoSelect' + this.id
                    })
                    .css('width', 150 + 'px')
                    .text('Select Photo'),

                    $('<input/>', {
                        id: 'photoSelect' + this.id,
                        type: 'file',
                        accept: 'image/png, image/jpeg'
                    })
                    .on('change',
                        function(e) {
                            var myId = e.target.id + '';
                            var id = myId.slice(11);
                            updateImg('profilePhoto' + id, myId);
                        })
                    .css('width', 0),

                    $('<div/>', {
                        id: 'deletePhotoButton' + this.id,
                        class: 'button photoButton'
                    })
                    .css('padding', 0 + 'px ' + 20 + 'px')
                    .on('click',
                        function(e) {
                            var myId = e.target.id + '';
                            var id = myId.slice(17);
                            console.log(myId + ' ' + id);
                            deleteImg('profilePhoto' + id);
                        })
                    .text('Delete')
                ),

                $('<div/>', {
                    id: 'profilePhoto' + this.id,
                    class: 'profilePhoto'
                })
                .append(
                    $('<img>', {
                        src: personImg
                    })
                )

            )
    }
}