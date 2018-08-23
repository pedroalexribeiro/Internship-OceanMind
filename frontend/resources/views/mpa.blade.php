<!DOCTYPE html>
<html>
<head>
	<title>{{ $mpa->name }} - Ocean in Motion</title>

	<script src="https:/ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https:/stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https:/stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="{{ URL::asset('js/Chart.bundle.js') }}"></script>
	<script src="https:/code.createjs.com/easeljs-0.8.2.min.js"></script>
    <script src="https:/code.createjs.com/tweenjs-0.6.2.min.js"></script>


	<style type="text/css">
		body {
			background-color: #23303d;
			margin-top: 130px;
		}
		#simulator {
		    background:url(/img/images/ocean.png);
		    background-size: 100% 100%;
		    border: 1px solid;
		    padding: 0;
		    margin: 0;
		    display: block;
		    position: absolute;
		    top: 0;
		    bottom: 0;
		    left: 0;
		    right: 0;
		}
	</style>

	<script type="text/javascript">
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
		let soundLevel = 0;
		let soundTime = null;
		var hours = [];
		var myLineChart;
		var updateChartInterval;
		var counter = 0;

		function loadChart() {
			var ctxL = document.getElementById("lineChart").getContext('2d');
			var date = new Date();
			var hour = date.getHours();
			
			/*
			for (let i = 0; i < 24; i++) {
				hour++;
				if(hour == 24)
					hour = 0;
				hours[i] = hour;
			}
			*/
			myLineChart = new Chart(ctxL, {
			    type: 'line',
			    data: {
			        labels: hours,
			        datasets: [
			            {
			            	backgroundColor: "rgba(255, 0, 0, 0.6)",
			            	borderColor: "rgba(255, 0, 0, 0.7)",
			            	pointBackgroundColor: "rgba(255, 0, 0, 0.8)",
			                data: hours/*{!! json_encode($mpa->threat_levels) !!}*/
			            },
			        ]
			    },
			    options: {
			    	legend: {
		    	        display: false
		    	    },
			    	scales: {
			    		yAxes: [{
			    			scaleLabel: {
			    				display: true,
			    				labelString: 'Sound Warning Level [0 - 10]',
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			},
			    			gridLines: {
			    				display: false
			    			},
			    			ticks: {
			    				min: 0,
			    				max: 10,
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			}
			    		}],
			    		xAxes: [{
			    			scaleLabel: {
			    				display: true,
			    				labelString: 'Time (min)',
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			},
			    			gridLines: {
			    				display: false
			    			},
			    			ticks: {
			    				fontColor: "#FFFFFF",
			    				fontSize: 13
			    			}
			    		}]
			    	},
			        responsive: true
			    }
			});
			updateChartInterval = setInterval(updateChart, 300);
		}
		function updateChart() {
			hours.push(soundLevel);
			myLineChart.update();
			counter++;
			if(counter > 24) {
				//clearInterval(updateChartInterval);
				hours.shift();
			}
		}

		(function () {
		    window.addEventListener("load", main);
		}());

		function main(){
		    simulationStart = createjs.Ticker.getTime(true);
		    soundTime = createjs.Ticker.getTime(true);
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
		    boat.x = 500;
		    boat.y = -100;
		    moveBitmaps(-100, 800, boat, 10000, false);
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

		function calculateSoundLevel(){
		    let allDown = true;
		    let sound = 0;
		    for(let index = 0; index < circles.length; ++index) {
		        let pt = boat.localToLocal(100, 0, circles[index]);
		        if (circles[index].hitTest(pt.x, pt.y)) {
		            allDown = false;
		            sound += Math.log(Math.pow((buoys[index].y - boat.y), 2) + Math.pow((buoys[index].x - boat.x), 2));
		        }
		    }
		    if(allDown){
		        sound = 0;
		    }
		    return sound;
		}

		function createPackage(x, y){
		    let pack = new Image();
		    pack.src = "/img/images/info.png";
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
		    boat.src = "/img/images/boat.png";
		    let boatBitmap = new createjs.Bitmap(boat.src);
		    boatBitmap.x = -100;
		    boatBitmap.y = -100;
		    boatBitmap.scaleX = 0.5;
		    boatBitmap.scaleY = 0.5;
		    boatBitmap.rotation = -50;
		    return boatBitmap;
		}

		function createBuoy(x, y){
		    let buoy = new Image();
		    buoy.src = "/img/images/buoy.png";
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
		    antenna.src = "/img/images/antenna.png";
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
		        calculateCollision();
		        stage.update(event);
		    }else{
		        boat.x = 500;
		        boat.y = -100;
		        simulationStart = createjs.Ticker.getTime(true);
		        moveBitmaps(-100, 800, boat, 10000, false);
		    }
		    if(currentTime - soundTime >= 300){
		        soundTime = currentTime;
		        soundLevel = calculateSoundLevel();
		        soundLevel = Math.round(soundLevel);
		    }
		}
	</script>
</head>
<body onload="loadChart()">

	@include('partials/navbar')

	<div class="container-fluid" style="margin-bottom: 30px;">
		<div class="row" style="margin-bottom: 30px;">
			<div class="col" align="center">
				<h1 style="color: white;">{{ $mpa->name }}</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<canvas id="simulator" width="750" height="600"></canvas>
			</div>
			<div class="col-6">
				<div class="row">
					<div class="col">
						<canvas id="lineChart"></canvas>
					</div>
				</div>
				<div class="row" align="center">
					<div class="col">
						<div class="row" style="border-top: solid 1px white; margin-top: 20px; padding-top: 20px; margin-bottom: 15px; color: white;" align="center">
							<div class="col">
								<h2>Highlights</h2>
							</div>
						</div>
						@foreach($mpa->event_highlights as $event)
						<div style="color: white; background-color: white; margin-bottom: 5px; padding: 15px; border-radius: 15px; width: 500px;">
							<div class="row">
								<div class="col">
									<h5 style="color: #23303d; font-weight: bold;">{{ $event[0] }}</h5>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<h6 style="color: #23303d;">{{ $event[1] }}</h6>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				
			</div>
		</div>
		
	</div>


</body>
</html>