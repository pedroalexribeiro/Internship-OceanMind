"use strict";
var stage;

(function () {
    window.addEventListener("load", main);
}());

function main(){
    stage = new createjs.Stage("simulator");
    stage.enableMouseOver();
    createjs.Ticker.setFPS(60);
    createjs.Ticker.addEventListener("tick", stage);

    var boats = new createjs.Text("New boat", "20px Georgia", "#000000");
    boats.x = 800 - boats.getMeasuredWidth();
    boats.y = 0;
    boats.on("click", newBoat);
    stage.addChild(boats);


    for (let i = 0; i < 4; i++) {
        let options = new Image();
        options.src = "boia.png";
        let bitmap = new createjs.Bitmap(options.src);
        bitmap.x = i*150 + 80;
        bitmap.y = 250;
        bitmap.scaleX = 0.1;
        bitmap.scaleY = 0.1;
        var circle = new createjs.Shape();
        circle.graphics.beginFill("Grey").drawCircle(0, 0, 75);
        circle.x = bitmap.x + 20;
        circle.y = bitmap.y + 50;
        stage.addChild(circle);
        stage.addChild(bitmap);
    }
}

function newBoat(){
    let options = new Image();
    options.src = "boat.png";
    let bitmap = new createjs.Bitmap(options.src);
    bitmap.x = 800;
    bitmap.y = 100;
    bitmap.scaleX = 0.5;
    bitmap.scaleY = 0.5;
    stage.addChild(bitmap);
    createjs.Tween.get(bitmap, {loop: false})
        .to({x: -150}, 7000, createjs.Ease.none);
}

function pointer(){
    stage.cursor = 'pointer';
}