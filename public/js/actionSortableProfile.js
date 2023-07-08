$('#save').on('click', () => {
    arrays = [];
    for (i = 0; i < 3; i++) {
        array = $('#column' + i)
            .sortable('toArray');
        console.log(array);
        arrays[i] = array;
    }
    updatePos(arrays);
    // location.reload();
})

$('#retrive').on('click', () => {
    for (i = 0; i < 3; i++) {
        $('#column' + i)
            .sortable('destroy');
    }
    $('ul').remove();
    buildSortable(itemsArr);
    // updatePos(arrays);
})

function updatePos(arrays) {
    sendQueryUpdatePos(arrays);
    console.log(arrays);
}