imgUrlInfo = LocalURL + 'public/icon/hint.svg';

function buildSortable(items) {
    for (i = 0; i < 3; i++) {
        $('<ul></ul>', {
                id: 'column' + i,
                class: 'column'
            })
            .sortable({
                scrollSensitvity: 1000,
                scrollSpeed: 50,
                containment: '#mainContainer',
                curser: 'move',
                delay: 200,
                forcePlaceholderSize: true,
                placeholder: "placeholder",
                revert: 500,
                tolerance: "pointer",
                zIndex: 10,
                connectWith: ['.column'],
                over: function(event, ui) {
                    $(this)
                        .css('background-color', 'var(--c5)');
                },
                out: function(event, ui) {

                },
                deactivate: function(event, ui) {
                    for (i = 0; i < 3; i++) {
                        $('#column' + i)
                            .css('background-color',
                                ($('#column' + i).children().length == 0) ?
                                'var(--c2)' : 'var(--c5)');
                    }
                }
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
    constructor(name) {
        return $('<div/>', {
                id: name,
                class: 'textInput',
            })
            .append(
                $('<div/>')
                .css({
                    'width': 232 + 'px',
                    'height': 21.6 + 'px',
                    'padding': 5 + 'px'
                }),

                $('<label/>', {
                    for: 'textInput' + TextInput.id
                })
                .text(name),

                $('<img>', {
                    src: imgUrlInfo
                })

            )
    }
}
personImg = LocalURL + 'public/icon/person.svg';

class Photo {
    constructor(name) {
        return $('<div/>', {
                id: name,
                class: 'photoController bgColor'
            })
            .css({
                'width': 290 + 'px',
                'height': 290 + 'px'
            })
            .append(
                $('<div/>', {
                    id: 'photoButtons',
                    class: 'row'
                })
                .append(
                    $('<label/>', {
                        id: 'photoSelectButton',
                        class: 'button newPhotoButton',
                    })
                    .text('Select Photo'),

                    $('<div/>', {
                        id: 'deletePhotoButton',
                        class: 'button newPhotoButton'
                    })
                    .text('Delete')
                ),

                $('<div/>', {
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