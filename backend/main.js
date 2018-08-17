"use strict";
let stage;
let boat = null;
let buoys = null;
let circles = null;
let antenna = null;

(function () {
    window.addEventListener("load", main);
}());

function main(){
    stage = new createjs.Stage("simulator");
    stage.enableMouseOver();
    createjs.Ticker.setFPS(60);
    boat = createBoat();
    antenna = createAntenna();
    buoys = new Array();
    circles = new Array();
    for(let i = 0; i<4; ++i){
        let buoy = createBuoy(250, i*150 + 65);
        let circle = createZone(250, i*150 + 65);
        buoys.push(buoy);
        circles.push(circle);
        stage.addChild(buoy);
        stage.addChild(circle);
    }
    stage.addChild(antenna);
    stage.addChild(boat);
    createjs.Ticker.addEventListener("tick", tick);
}

function calculateCollision(){
    for(let index = 0; index < circles.length; ++index) {
        let pt = boat.localToLocal(100, 0, circles[index]);
        if (circles[index].hitTest(pt.x, pt.y)) {
            circles[index].alpha = 1;
            if(!info[index]) {
                createPackage(circles[index].x, circles[index].y, index);
                info[index] = true;
            }
        }
        else {
            info[index] = false;
            if (tweens[index] != null) {
                createjs.Tween.removeTweens(tweens[index]);
                tweens[index].alpha = 0;
            }
            circles[index].alpha = 0.2
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
    boatBitmap.rotation = 55 + 180;
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
    createjs.Tween.get(bitmap, {loop: loop})
        .to({x: destinationX, y: destinationY}, time, createjs.Ease.none);
}

function tick(event) {
    //calculateCollision(boat, circles);
    stage.update(event);
}