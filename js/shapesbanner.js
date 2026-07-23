class Star {
    constructor(xpos, ypos, spinX, spinY, tranX, tranY, tranZ) {
        this.x = xpos;
        this.y = ypos;
        this.rotx = spinX;
        this.roty = spinY;
        this.tranx = tranX;
        this.trany = tranY;
        this.tranz = tranZ;
        this.stargeometry = new THREE.DodecahedronGeometry(0.2)
        this.starwireframe = new THREE.EdgesGeometry(this.stargeometry);
        this.starmat = new THREE.LineBasicMaterial({ color: "rgb(" + random(130, 176) + ", " + random(170, 218) + ", " + random(50, 94) + ")" });
        this.starline = new THREE.LineSegments(this.starwireframe, this.starmat);
        this.starline.position.x = this.x
        this.starline.position.y = this.y
        this.starline.position.z = -0.1
        //this.satrline.speeds;
        this.starline.speeds = [this.tranx, this.trany, this.tranz, this.rotx, this.roty, 0.5]
        scene.add(this.starline)
    }

}

const scene = new THREE.Scene();
scene.background = new THREE.Color(0x2F2D2E);
//const camera = new THREE.OrthographicCamera( 12, -12,11, -11, 0.1, 100 );
const camera = new THREE.PerspectiveCamera(60, window.innerWidth / (window.innerWidth / 3), 1, 3000);
const renderer = new THREE.WebGLRenderer();
//renderer.setSize(window.innerWidth, window.innerWidth / 3);


var starArray = [];
for (let i = 0; i < 25; i++) {
    starArray.push(new Star(random(-17, 17),
            random(-5, 5),
            Math.random() * 0.1, Math.random() * 0.08,
            Math.random() * 0.008 * (-1 + Math.round(Math.random()) * 2),
            Math.random() * 0.01 * (-1 + Math.round(Math.random()) * 2)),
        -10)
    //scene.add(starArray[starArray.length-1]);
}

const geometry = new THREE.TetrahedronGeometry()
const wireframe = new THREE.EdgesGeometry(geometry);
var mat = new THREE.LineBasicMaterial({ color: 0x048BA8 });
const line = new THREE.LineSegments(wireframe, mat);
line.speeds = [0.08, -0.05, 0, 0.01, 0.01, 1.4]
scene.add(line);

const dodecgeometry = new THREE.DodecahedronGeometry()
const dodecwireframe = new THREE.EdgesGeometry(dodecgeometry);
var dodecmat = new THREE.LineBasicMaterial({ color: 0xF18F01 });
const dodecline = new THREE.LineSegments(dodecwireframe, dodecmat);
dodecline.speeds = [0.05, 0.07, 0, 0.01, 0.01, 1.5]
scene.add(dodecline);

const cubegeometry = new THREE.BoxGeometry()
const cubewireframe = new THREE.EdgesGeometry(cubegeometry);
var cubemat = new THREE.LineBasicMaterial({ color: 0xffffff });
const cubeline = new THREE.LineSegments(cubewireframe, cubemat);
cubeline.speeds = [0.06, -0.02, 0, 0.01, 0.01, 1.2]
scene.add(cubeline);

camera.position.z = 10;
frustumPlanes = checkOnScreen();
boundX = (visibleBox(10) / 2);
boundY = frustumPlanes[3].constant - 0.1;
document.getElementById("shapes").appendChild(renderer.domElement); //where to stuff canvas tag
window.addEventListener('resize', onWindowResize);


function animate() {
    requestAnimationFrame(animate);


    for (var i = 0; i < scene.children.length; i++) {

        var object = scene.children[i];
        //let pos = getPos(object)
        if (object.position.x > boundX - object.speeds[5] || object.position.x < -1 * boundX + object.speeds[5]) {
            object.speeds[0] *= -1;
        }
        if (object.position.y > boundY || object.position.y < -1 * boundY) {
            object.speeds[1] *= -1;
        }
        //checkOnScreen(object)
        object.position.x += object.speeds[0];
        object.position.y += object.speeds[1];
        object.rotateY(object.speeds[4])
        object.rotateX(object.speeds[3])

    }

    renderer.render(scene, camera);
}
animate();


function visibleBox(z) {
    // var t = Math.tan(THREE.Math.degToRad(camera.fov) / 2)
    // var h = t * 2 * z;
    // var w = h * camera.aspect;
    // return new THREE.Vector2(w, h);
    var vFOV = THREE.MathUtils.degToRad(camera.fov); // convert vertical fov to radians

    var height = 2 * Math.tan(vFOV / 2) * z // visible height

    var width = height * camera.aspect; // visible width
    return width;
}

function checkOnScreen(obj) {
    camera.updateMatrix();
    camera.updateMatrixWorld();
    var frustum = new THREE.Frustum();
    frustum.setFromProjectionMatrix(new THREE.Matrix4().multiplyMatrices(camera.projectionMatrix, camera.matrixWorldInverse));
    return frustum.planes
    // Your 3d point to check
    // var pos = new THREE.Vector3(obj.position.x, obj.position.y, obj.position.z);
    // if (!frustum.containsPoint(pos)) {
    //     // Do something with the position...
    //     console.log("hello")
    // }
}

function random(min, max, noRound, occlusion) { //Homebrew random function for generating random numbers, occlusion is an array that holds #s our random cant be
    if (noRound) {
        this.value = Math.random() * (max - min) + min;
    } else {
        this.value = Math.round(Math.random() * (max - min)) + min;
    }
    if (occlusion) {
        for (let i = 0; i < occlusion.length; i++) {
            if (this.value == occlusion[i]) {
                this.value = random(min, max, occlusion); //Recursive function to cycle until it finds our number
            }
        }
        return this.value;
    } else {
        return this.value;
    }
}