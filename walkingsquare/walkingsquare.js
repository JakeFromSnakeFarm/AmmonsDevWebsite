var square = document.querySelector(".walkingSquare");
var inc = window.scrollY;
setInterval(function() {
    inc = window.scrollY;
    square.style.setProperty('--rotations', inc);
    square.style.setProperty('--yoffset', -1 * calcHeightOffset(50, inc) + "px");
}, 10);

function calcHeightOffset(height, angle) {
    let deltah = ((Math.sqrt(2) * height) / 2) - (height / 2);
    let cleanang = angle % 90;
    let anglemap = -(Math.pow(((cleanang - 45) / 45), 2)) + 1;
    let h = anglemap * deltah;
    return h;
}