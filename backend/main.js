"use strict";
var stage;
var boat = null;
var circles = new Array();
var info = [false, false, false, false];
var tweens = [null, null, null, null];

(function () {
    window.addEventListener("load", main);
}());

function main(){
    stage = new createjs.Stage("simulator");
    stage.enableMouseOver();
    createjs.Ticker.setFPS(60);
    createjs.Ticker.addEventListener("tick", tick);
    let options = new Image();
    options.src = "antenna.png";
    let bitmap = new createjs.Bitmap(options.src);
    bitmap.x = 700;
    bitmap.y = 300;
    bitmap.scaleX = -0.15;
    bitmap.scaleY = 0.15;
    stage.addChild(bitmap);

    let boats = new createjs.Text("New boat", "20px Georgia", "#000000");
    boats.x = 800 - (boats.getMeasuredWidth() + 20);
    boats.y = 20;
    let hit = new createjs.Shape();
    hit.graphics.beginFill("#000").drawRect(0, 0, boats.getMeasuredWidth(), boats.getMeasuredHeight());
    boats.hitArea = hit;
    boats.shadow = new createjs.Shadow("#000000", 5, 5, 10);
    boats.on("click", newBoat);
    boats.on("mouseover", buttonHandler);
    boats.on("mouseout", buttonHandler);
    stage.addChild(boats);



    for (let i = 0; i < 4; i++) {
        let options = new Image();
        options.src = "boia.png";
        let bitmap = new createjs.Bitmap(options.src);
        bitmap.x = 250;
        bitmap.y = i*150 + 80;
        bitmap.scaleX = 0.02;
        bitmap.scaleY = 0.02;
        let circle = new createjs.Shape();
        circle.graphics.beginFill("Grey").drawCircle(0, 0, 75);
        circle.x = bitmap.x + 5;
        circle.y = bitmap.y + 10;
        circle.alpha = 0.2;
        circles.push(circle);
        stage.addChild(circle);
        stage.addChild(bitmap);
    }
}

function newBoat(){
    let options = new Image();
    options.src = "boat.png";
    let bitmap = new createjs.Bitmap(options.src);
    bitmap.x = -100;
    bitmap.y = -100;
    bitmap.scaleX = 0.5;
    bitmap.scaleY = 0.5;
    bitmap.rotation = 55 + 180;
    boat = bitmap;
    stage.addChild(bitmap);
    createjs.Tween.get(bitmap, {loop: false})
        .to({x: 500, y: 800}, 10000, createjs.Ease.none);
}

function buttonHandler(ev){
    ev.target.alpha = (ev.type === "mouseover") ? 1 : 0.8;
    ev.target.shadow = (ev.type === "mouseover") ? new createjs.Shadow("#000000", 15, 15, 10) : new createjs.Shadow("#000000", 5, 5, 10);
}

function tick(event) {
    if(boat != null) {
        calculateCollision(boat, circles);
    }
    stage.update(event);
}

function calculateCollision(){
    for(let index = 0; index < circles.length; ++index) {
        let pt = boat.localToLocal(100, 0, circles[index]);
        if (circles[index].hitTest(pt.x, pt.y)) {
            circles[index].alpha = 1;
            if(!info[index]) {
                createInfo(circles[index].x, circles[index].y, index);
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

function createInfo(x, y, index){
    let options = new Image();
    options.src = "info.png";
    let bitmap = new createjs.Bitmap(options.src);
    bitmap.x = x + 20;
    bitmap.y = y - 42;
    bitmap.scaleX = 0.1;
    bitmap.scaleY = 0.1;
    createjs.Tween.get(bitmap, {loop: true})
        .to({x: 700, y: 300}, 500, createjs.Ease.none);
    tweens[index] = bitmap;
    stage.addChild(bitmap);
}
