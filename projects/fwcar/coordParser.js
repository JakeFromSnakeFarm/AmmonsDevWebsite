const fs = require('fs');

fs.readFile("./gpsCoords/coords.txt", 'utf8', function(err, data) {
    if (err) throw err;
    let temp = "",
        arr = [],
        parsedCoords = [],
        objArr = [];
    temp = data;
    arr = temp.split("\r");
    for (let i = 0; i < arr.length; i++) {
        if (arr[i][arr[i].length - 1] == "w" || arr[i][arr[i].length - 1] == "m") {
            console.log(i)
            arr.splice(i, 1);
            i--;
        }
    }
    console.log(arr);

    //Seperate Data
    for (let i = 0; i < arr.length; i++) {
        let count = 0,
            tempString = "";
        for (let j = arr[i].length - 1; j > 0; j--) {
            tempString = arr[i][j] + tempString;
            if (arr[i][j] == " ") {
                count++;
            }
            if (count == 10) {
                //Parsing Number Data
                //console.log(tempString)
                let tempNumData = tempString.split(" ");
                //console.log(tempNumData)
                //Objectifying
                if (tempNumData[3] == "UNK") {
                    //console.log("unk")
                } else {
                    objArr.push({
                        nameData: "" + (arr[i].substring(1, arr[i].length - tempString.length)),
                        tons: tempNumData[1],
                        depth: tempNumData[3],
                        relief: tempNumData[2],
                        lat: tempNumData[4] + tempNumData[5],
                        lon: tempNumData[7] + tempNumData[8]
                    });
                }
                break;
            }
        }
    }
    //console.log(objArr)

    fileWrite(objArr);
});

function fileWrite(toString) {
    fs.writeFile('./gpsCoords/coordsP.txt', JSON.stringify(toString), function(err) {
        if (err) return console.log(err);
        console.log('File pushed');
    });
}
//