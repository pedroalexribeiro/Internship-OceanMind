"use strict";
let stage;
let boat = null;
let buoys = null;
let circles = null;
let antenna = null;
let simulationStart = null;
let currentTime = null;
let packages = null;
let packagesTween = null;

(function () {
    window.addEventListener("load", main);
}());

function main(){
    simulationStart = createjs.Ticker.getTime(true);
    stage = new createjs.Stage("simulator");
    stage.enableMouseOver();
    createjs.Ticker.setFPS(60);
    boat = createBoat();
    antenna = createAntenna();
    buoys = new Array();
    circles = new Array();
    packages = new Array();
    packagesTween = [null, null, null, null];
    for(let i = 0; i<4; ++i){
        let buoy = createBuoy(250, i*150 + 65);
        let circle = createZone(250, i*150 + 65);
        let pack = createPackage(250, i*150 + 65);
        buoys.push(buoy);
        circles.push(circle);
        packages.push(pack);
        stage.addChild(pack);
        stage.addChild(buoy);
        stage.addChild(circle);
    }
    stage.addChild(antenna);
    stage.addChild(boat);
    boat.x = -100;
    boat.y = 800;
    moveBitmaps(500, -100, boat, 10000, false);
    createjs.Ticker.addEventListener("tick", tick);
}

function calculateCollision(){
    for(let index = 0; index < circles.length; ++index) {
        let pt = boat.localToLocal(100, 0, circles[index]);
        if (circles[index].hitTest(pt.x, pt.y)) {
            circles[index].alpha = 0.8;
            if(!packagesTween[index]) {
                packages[index].alpha = 1;
                packagesTween[index] = moveBitmaps(antenna.x, antenna.y, packages[index], 300, true);
            }
        }
        else {
            if (packagesTween[index] != null) {
                createjs.Tween.removeTweens(packagesTween[index]);
                packagesTween[index] = null;
                packages[index].alpha = 0;
            }
            circles[index].alpha = 0.2;
        }
    }
}

function createPackage(x, y){
    let pack = new Image();
    pack.src = "images/info.png";
    let packBitmap = new createjs.Bitmap(pack.src);
    packBitmap.x = x + 20;
    packBitmap.y = y - 42;
    packBitmap.scaleX = 0.1;
    packBitmap.scaleY = 0.1;
    packBitmap.alpha = 0;
    return packBitmap;
}

function createBoat(){
    let boat = new Image();
    boat.src = "images/boat.png";
    let boatBitmap = new createjs.Bitmap(boat.src);
    boatBitmap.x = -100;
    boatBitmap.y = -100;
    boatBitmap.scaleX = 0.5;
    boatBitmap.scaleY = 0.5;
    boatBitmap.rotation = 130;
    return boatBitmap;
}

function createBuoy(x, y){
    let buoy = new Image();
    buoy.src = "images/buoy.png";
    let buoyBitmap = new createjs.Bitmap(buoy.src);
    buoyBitmap.x = x;
    buoyBitmap.y = y;
    buoyBitmap.scaleX = 0.02;
    buoyBitmap.scaleY = 0.02;
    return buoyBitmap;
}

function createZone(x, y){
    let circle = new createjs.Shape();
    circle.graphics.beginFill("Grey").drawCircle(0, 0, 75);
    circle.x = x + 5;
    circle.y = y + 10;
    circle.alpha = 0.2;
    return circle;
}

function createAntenna(){
    let antenna = new Image();
    antenna.src = "images/antenna.png";
    let antennaBitmap = new createjs.Bitmap(antenna.src);
    antennaBitmap.x = 700;
    antennaBitmap.y = 300;
    antennaBitmap.scaleX = -0.15;
    antennaBitmap.scaleY = 0.15;
    return antennaBitmap;
}

function moveBitmaps(destinationX, destinationY, bitmap, time, loop){
    return createjs.Tween.get(bitmap, {loop: loop})
        .to({x: destinationX, y: destinationY}, time, createjs.Ease.none);
}

function tick(event) {
    currentTime = createjs.Ticker.getTime(true);
    if(currentTime - simulationStart <= 11000){
        calculateCollision(boat, circles);
        stage.update(event);
    }else{
        boat.x = -100;
        boat.y = 800;
        simulationStart = createjs.Ticker.getTime(true);
        moveBitmaps(500, -100, boat, 10000, false);
    }

}