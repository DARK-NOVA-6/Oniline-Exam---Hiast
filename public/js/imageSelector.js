function deleteImg(PhotoContainerId) {
    $("#" + PhotoContainerId + " img").attr('src', LocalURL + 'public/icon/person.svg');
}

function updateImg(photoContainerId, fileInputId) {
    var files = document.querySelector("#" + fileInputId).files;
    $("#" + photoContainerId + " img").attr('src', URL.createObjectURL(files[0]));
}