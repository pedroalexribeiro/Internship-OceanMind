<style type="text/css">
	.navbar {
		height: 100px;
		background-color: white;
		position:fixed;
		top:0px;
		z-index: 15;
		-webkit-box-shadow: 0px 0px 20px rgba(0,0,0,.5);
	   	-moz-box-shadow: 0px 0px 20px rgba(0,0,0,.5);
	    box-shadow: 0px 3px 20px rgba(0,0,0,.5);
	}
</style>

<div class="container-fluid navbar">
	<div class="row" style="width: 100%; margin: 0;" align="center">
		<div class="col-12">
			<a href="{{ URL::to('/') }}"><img src="{{ URL::to('/') }}/img/logo.png" height="55"></a>
		</div>
	</div>
</div>