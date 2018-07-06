var canvas = document.getElementById('canvas'),
    ctx = canvas.getContext('2d'),
    moveflg = 0,
    Xpoint,
    Ypoint;

//Pen Size and Color
var defSize = 4;
var defColor = "#000000";

// Get Canvas position
var element = document.getElementById( "canvas" );
var rect = element.getBoundingClientRect();
var positionX = rect.left + window.pageXOffset;
var positionY = rect.top + window.pageYOffset;

// Deal with PC
canvas.addEventListener('mousedown', startPoint, false);
canvas.addEventListener('mousemove', movePoint, false);
canvas.addEventListener('mouseup', endPoint, false);

// Deal with SmartPhone
canvas.addEventListener('touchstart', startPoint, false);
canvas.addEventListener('touchmove', movePoint, false);
canvas.addEventListener('touchend', endPoint, false);

function startPoint(e){
	e.preventDefault();
	ctx.beginPath();
	Xpoint = e.pageX - positionX;
	Ypoint = e.pageY - positionY;
	ctx.moveTo(Xpoint, Ypoint);
}

function movePoint(e){
	if(e.buttons === 1 || e.witch === 1 || e.type == 'touchmove'){
		Xpoint = e.pageX - positionX;
		Ypoint = e.pageY - positionY;
		moveflg = 1;
		ctx.lineTo(Xpoint, Ypoint);
		ctx.lineCap = "round";
		ctx.lineWidth = defSize * 2;
		ctx.strokeStyle = defColor;
		ctx.stroke();
	}
}

function endPoint(e){
    if(moveflg === 0){
		ctx.lineTo(Xpoint-1, Ypoint-1);
	    ctx.lineCap = "round";
	    ctx.lineWidth = defSize * 2;
	    ctx.strokeStyle = defColor;
	    ctx.stroke();
	}
    moveflg = 0;
}


function chgImg(){
	var png = canvas.toDataURL();
	document.getElementById("newImg").src = png;
	/*
	var element = document.getElementById('content');
    var message = document.createElement('div');
    message.setAttribute('class', 'alert alert-success alert-block');
    message.innerHTML = '<a class="close" data-dismiss="alert" href="#">×</a><h4 class="alert-heading">Success!</h4>Register Image.';
    element.insertBefore(message, element.firstChild);
    */
}
function colorCange(colortype){
	if(colortype===0){
		defColor = "#000000";
	} else {
		defColor = "#ffffff";
	}
}