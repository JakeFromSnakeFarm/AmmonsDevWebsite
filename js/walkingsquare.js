var square = document.querySelector(".walkingSquare");
var inc = window.scrollY;
var squareHeight = 75;
square.style.setProperty('--width', squareHeight+'px');
console.log(squareHeight + " "+ getComputedStyle(square).getPropertyValue('--width'))
setInterval(function() {
    inc = window.scrollY;
    if(inc > navChangeVal-110) {
        $("#headerContainer").css("backgroundColor", "var(--darkgray)");
        $("#logoWhite").css("opacity", 0);
    } else {
        $("#headerContainer").css("backgroundColor", "unset");
        $("#logoWhite").css("opacity", 1);
    }
    square.style.setProperty('--rotations', inc);
    square.style.setProperty('--yoffset', -1 * calcHeightOffset(squareHeight, inc) + "px");
}, 1000/60);

function calcHeightOffset(height, angle) {
    let deltah = ((Math.sqrt(2) * height) / 2) - (height / 2);
    let cleanang = angle % 90;
    let anglemap = -(Math.pow(((cleanang - 45) / 45), 2)) + 1;
    //let anglemap = -Math.cosh((cleanang-45)/34.1697)+2;
    let h = anglemap * deltah;
    return h;
}

function updateSquareHeight(newHeight) {
    squareHeight = newHeight;
    square.style.setProperty('--width', squareHeight+'px');
}