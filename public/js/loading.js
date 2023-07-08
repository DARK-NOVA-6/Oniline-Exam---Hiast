function startLoading() {
    var curr = 0;
    $('#screenDisable1').attr('style', '');
    $('#boxRotatingLoadin').addClass('rotateLoading');
    $('#boxScalingLoadin').addClass('scaleLoading');
    var curr = 1;
    $('#whiteShape1').attr('src', LocalURL + 'public/icon/loading/white_' + curr + '.svg');
    $('#whiteShape2').attr('src', LocalURL + 'public/icon/loading/white_' + curr + '.svg');
    $('#blueShape').attr('src', LocalURL + 'public/icon/loading/blue_' + curr + '.svg');
    setTimeout(() => {

        setInterval(() => {
            $('#whiteShape1').attr('src', LocalURL + 'public/icon/loading/white_' + curr + '.svg');
            $('#whiteShape2').attr('src', LocalURL + 'public/icon/loading/white_' + curr + '.svg');
            $('#blueShape').attr('src', LocalURL + 'public/icon/loading/blue_' + curr + '.svg');
            curr = Math.floor(Math.random() * 3);
        }, 3000);
    }, 1500);
}

function stopLoading() {
    $('#screenDisable1').attr('style', 'display: none;');
    $('#boxRotatingLoadin').removeClass('rotateLoading');
    $('#boxScalingLoadin').removeClass('scaleLoading');

}

function finishLoading() {
    $('#screenDisable1').attr('style', 'display: none;');
    $('#boxRotatingLoadin').removeClass('rotateLoading');
    $('#boxScalingLoadin').removeClass('scaleLoading');
}