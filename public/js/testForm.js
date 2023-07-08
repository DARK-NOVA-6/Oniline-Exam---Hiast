function buildDate() {
    $('#startDate').datepicker();
    $('#startDate').datepicker('show');

}

$('#addTc')
    .on('click',
        () => {
            var id = 0;
            if ($('body').data('generateId') === undefined) {
                $('body').data('generateId', 0);
            } else {
                id = $('body').data('generateId');
                id++;
                $('body').data('generateId', id);
            }
            $('tcList').append(
                (new AutoComplete(id)).returnMe()
            );
        }
    )


class AutoComplete {
    constructor(id) {
        var ac = $('<div/>', {
            class: 'autoC',
            id: id + 'con'
        }).append(
            $('<input/>', {
                id: id,
                type: 'text'
            })
        )
        this.id = id;
        this.ac = ac;
    }
    returnMe() {
        return this.ac;
    }
}