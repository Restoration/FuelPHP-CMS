<?php include APPPATH . 'views/parts/header.php';?>

<div class="row-fluid"></div>

<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-pencil"></i>Canvas</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">

				<canvas id="canvas" width="500" height="300"></canvas>

				<div class="right_link">
					<button type="button" class="form-control btn btn-primary" onclick="colorCange(0)">Pen</button>
					<button type="button" class="form-control btn btn-danger" onclick="colorCange(1)" >Eraser</button>
					<button type="button" class="form-control btn btn-success" onclick="chgImg()" value="1">Register</button>
				</div>
	        </div>
		</div>
	</div>
	<!-- /block -->

	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-picture"></i>Image</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
				<div id="img-box"><img id="newImg"></div>
	        </div>
		</div>
	</div>
	<!-- /block -->

</div>
<?php echo \Asset::js('canvas.js');?>
<?php include APPPATH . 'views/parts/footer.php';?>