
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>CupaLigii.ro</title>
    <style>
    	* { padding: 0; margin: 0; }
    	canvas { background: lightgreen; display: block; margin: 0 auto; }
    </style>
</head>
<body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
var auto_refresh = setInterval(
function()
{
var d = new Date();
var n = d.getMinutes(); 
//$('#valoridinfisier').fadeOut('slow').load('_test.txt').fadeIn("slow");
$('#valoridinfisier').fadeOut('slow').load('_testmecinou.php?minut='+n).fadeIn("slow");
i=0;
}, 10000);
</script>



<canvas id="myCanvas" width="800" height="600">
<!--
<img id="myimg1a" width="60" height="60">
<img id="myimg2a" src="p35b.png" width="60" height="60">
<img id="myimg2b" src="p36b.png" width="60" height="60">
-->
<img id="minge" src="minge.png" width="60" height="60">
</canvas>

<textarea id="valoridinfisier" style="display: none;" name="valoridinfisier"></textarea>


<script>
	// JavaScript code goes here
var canvas = document.getElementById("myCanvas");
var ctx = canvas.getContext("2d");

//in functie de collision, fac dupa cite atingeri se incheie faza.
//random eventual cind se ajunge la un numar, sa suteze.
//in momentul in care suteaza, nu se mai atinge de nimeni.
var collision = 0;

//in momentul in care vreau sa inchei faza, sa nu se mai loveasca de nimeni
var faracoliziuni = 0;

var step=0;

var dx = 2;
var dy = -2;

var j1=0;
var j2=0;

//in subtitle
var mesaj = "";

var x = canvas.width/2;
var y = canvas.height-30;
var ballRadius = 30;


//array-uri in care se gasesc pozitiile jucatorilor
var x1 =new Array(13), y1=new Array(13), x2 = new Array(13), y2=new Array(13);

var i=0;

function drawRest() {


//ctx.beginPath();
//ctx.rect(0, 20, 150, 50);
//ctx.fillStyle = "#FF0000";
//ctx.fill();
//ctx.closePath();

//linie centru
ctx.beginPath();
ctx.moveTo(400,0);
ctx.lineTo(400,600);
ctx.stroke();
ctx.closePath();

//careu stinga
ctx.beginPath();
ctx.rect(0, 200, 100, 220);
ctx.fillStyle = "#FF0000";
ctx.stroke();
ctx.closePath();

//careu dreapta
ctx.beginPath();
ctx.rect(700, 200, 100, 220);
ctx.fillStyle = "#FF0000";
ctx.stroke();
ctx.closePath();


//cerc centru
ctx.beginPath();
ctx.arc(400,300,80,0,2*Math.PI);
ctx.stroke();


//cerc careu1
ctx.beginPath();
ctx.arc(100,305,30,1.5*Math.PI,0.5*Math.PI);
ctx.stroke();

//cerc careu1
ctx.beginPath();
ctx.arc(700,305,30,0.5*Math.PI,1.5*Math.PI);
ctx.stroke();



ctx.font = "24px Arial";
var deafisat = "i="+i+"; coll="+collision;
ctx.fillText(deafisat,335,50);

var text = $("#valoridinfisier").val();  
ctx.fillText(text,35,570);

var textarea = $('#valoridinfisier');
//textarea.hide();

}

function definePositions() {
	
	//echipa 1
	//portar1
	x1[0] = 10;
	y1[0] = 280;

	//fundas1
	x1[1] = Math.floor((Math.random() * 100) + 90);
	y1[1] = Math.floor((Math.random() * 100) + 1);


	//fundas2
	x1[2] = Math.floor((Math.random() * 100) + 90);
	y1[2] = Math.floor((Math.random() * 100) + 150);
	
	
	//fundas3
	x1[3] = Math.floor((Math.random() * 100) + 90);
	y1[3] = Math.floor((Math.random() * 100) + 270);

	
	//fundas4
	x1[4] = Math.floor((Math.random() * 100) + 90);
	y1[4] = Math.floor((Math.random() * 100) + 350);



	//mij1
	x1[5] = Math.floor((Math.random() * 100) + 390);
	y1[5] = Math.floor((Math.random() * 100) + 1);

	//mij2
	x1[6] = Math.floor((Math.random() * 100) + 390);
	y1[6] = Math.floor((Math.random() * 100) + 150);

	//mij3
	x1[7] = Math.floor((Math.random() * 100) + 390);
	y1[7] = Math.floor((Math.random() * 100) + 270);
	
	//mij4
	x1[8] = Math.floor((Math.random() * 100) + 390);
	y1[8] = Math.floor((Math.random() * 100) + 350);

	//at1
	x1[9] = Math.floor((Math.random() * 100) + 590);
	y1[9] = Math.floor((Math.random() * 100) + 150);

	//at2
	x1[10] = Math.floor((Math.random() * 100) + 590);
	y1[10] = Math.floor((Math.random() * 100) + 270);


	//echipa2
	//portar2
	x2[0] = 750;
	y2[0] = 280;

	/*
	
	//fundas1
	x2[1] = Math.floor((Math.random() * 100) + 620);
	y2[1] = Math.floor((Math.random() * 100) + 1);

	//fundas2
	x2[2] = Math.floor((Math.random() * 100) + 620);
	y2[2] = Math.floor((Math.random() * 100) + 150);

	//fundas3
	x2[3] = Math.floor((Math.random() * 100) + 620);
	y2[3] = Math.floor((Math.random() * 100) + 270);
	
	//fundas4
	x2[4] = Math.floor((Math.random() * 100) + 620);
	y2[4] = Math.floor((Math.random() * 100) + 450);


	//mij1
	x2[5] = Math.floor((Math.random() * 100) + 350);
	y2[5] = Math.floor((Math.random() * 100) + 1);

	//mij2
	x2[6] = Math.floor((Math.random() * 100) + 350);
	y2[6] = Math.floor((Math.random() * 100) + 150);

	//mij3
	x2[7] = Math.floor((Math.random() * 100) + 350);
	y2[7] = Math.floor((Math.random() * 100) + 270);
	
	//mij4
	x2[8] = Math.floor((Math.random() * 100) + 350);
	y2[8] = Math.floor((Math.random() * 100) + 450);

	//at1
	x2[9] = Math.floor((Math.random() * 100) + 120);
	y2[9] = Math.floor((Math.random() * 100) + 150);

	//at2
	x2[10] = Math.floor((Math.random() * 100) + 120);
	y2[10] = Math.floor((Math.random() * 100) + 270);
	*/
	
	j1 = Math.floor((Math.random() * 10) + 1); 
	j2 = Math.floor((Math.random() * 10) + 1); 
}

function drawPlayers() {

//imaginea cu jucator 1,2,3
/*
var img1a = document.getElementById("myimg1a");
var img2a = document.getElementById("myimg2a");
var img2b = document.getElementById("myimg2b");
*/
//preiau din fisier imaginile, denumirile
var arrayOfLines = $('#valoridinfisier').val().split(',');
var n1 = arrayOfLines[2].trim();
n1 = "Giginho";

if(i<2) {
	definePositions();
	
	/*
	img1a.src="images/"+arrayOfLines[3].trim();
	
	jx1 = Math.floor((Math.random() * 100) + 1); 
	jy1 = Math.floor((Math.random() * 100) + 1); 

	jx2 = Math.floor((Math.random() * 100) + 500); 
	jy2 = Math.floor((Math.random() * 100) + 200); 

	jx3 = Math.floor((Math.random() * 100) + 500); 
	jy3 = Math.floor((Math.random() * 100) + 300); 
	
	*/
	
}
//afisare poza juc 1
for(var j=0; j<x1.length;j++) {
	var img=new Image();
	img.src="images/"+arrayOfLines[3].trim();
    img.src = "images/p21.png";
	ctx.drawImage(img,x1[j],y1[j],40,40);
	ctx.font="8px Verdana";

		//afisare dreptunghi si nume
	ctx.beginPath();
	ctx.fillStyle = 'red';
	ctx.fillText(n1,x1[j],y1[j]+50);
	
	ctx.lineWidth="1";
	ctx.strokeStyle="red";
	ctx.rect(x1[j]-2,y1[j]-2,44,58);
	ctx.stroke();
	ctx.closePath();
}
for(var j=0; j<x2.length;j++) {
	var img=new Image();
	img.src="images/"+arrayOfLines[6].trim();
    img.src = "images/p22.png";
    ctx.drawImage(img,x2[j],y2[j],40,40);

	//afisare dreptunghi si nume
	ctx.beginPath();
	ctx.lineWidth="1";
	ctx.strokeStyle="blue";
	ctx.rect(x2[j]-2,y2[j]-2,44,58);
	ctx.stroke();
	ctx.font="8px Verdana";
	ctx.fillStyle = 'blue';
	ctx.fillText(n1,x2[j],y2[j]+50);

	ctx.closePath();

}
/*
ctx.drawImage(img2a,x2[0],y2[0],40,40);
ctx.drawImage(img2b,jx3,jy3,40,40);
*/
}


function drawBall() {
	var minge = document.getElementById("minge");
	ctx.beginPath();
	ctx.drawImage(minge,x,y);
	ctx.closePath();
}

function drawSubtitle(deafis) {
	ctx.beginPath();
	ctx.strokeStyle="white";
	ctx.fillRect(40,540,720,40);
	ctx.closePath();
	
	ctx.beginPath();
	ctx.font="16px Verdana";
	ctx.fillStyle = 'green';
	ctx.fillText(deafis,100, 570);
	
	ctx.closePath();
}

function draw() {

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawRest();
    drawPlayers();
    drawBall();
    drawSubtitle(mesaj);
    //lovirea de margini
    if(x + dx > canvas.width-ballRadius || x + dx < ballRadius) {
        dx = -dx;
		collision++;
    }
	if(y + dy > canvas.height-ballRadius || y+dy < ballRadius) {
        dy = -dy;
		collision++;
		
    }
    //end lovire de margini

	//lovirea de jucatori prima echipa
	//var j1=0;
	
	var imin=0;
	var deafisat = "";
	if (faracoliziuni == 0) {
		for (j = 0; j < x1.length; j++) {
			if(x> x1[j]-30 && x< x1[j]+30 && y>y1[j]-30 && y<y1[j]+30) {
				j2 = Math.floor((Math.random() * 10) + 1); 
				//if(x1[j2]<x1[j]) 
				dx = -dx;
				
				//in acest punct trebuie sa-i dau mingii coordonatele de plecare catre jucatorul urmator
				x=x1[j]+40;
				y=y1[j]+40;
				j1=j;
				imin=1;
				//final
				collision++;
				
				var rrand = Math.floor((Math.random() * 10) + 1);
				switch(rrand) {
					case 1: mesaj = "Mingea se afla in posesie celor de la minge..."; break;
					case 2: mesaj = "Pasa pentru jucatorul aflat langa..."; break;
					case 3: mesaj = "Incercare de sut..."; break;
					case 4: mesaj = "Posesie prelungita..."; break;
					case 5: mesaj = "Jocul capata brusc accente dramatice..."; break;
					case 6: mesaj = "Pasa este aproape interceptata..."; break;
					case 7: mesaj = "Minge in adancime si..."; break;
					case 8: mesaj = "Jucatorii reusesc sa tina posesia..."; break;
					case 9: mesaj = "Jocul este antrenant in aceste momente..."; break;
					case 10: mesaj = "Faza foarte buna pentru cei care se afla in atac..."; break;
				}
				drawSubtitle(mesaj);
				
			}
			if(imin==1) break;
			
		}
	}
    //end lovire de jucator1

	

	
	
	//var j2 = 0;
	//lovirea de jucatori a doua echipa
	imin=0;
/*
	for (j = 0; j < 10; j++) {
		if(x> x2[j] && x< x2[j]+40 && y>y2[j] && y<y2[j]+40) {
			//afla unde se duce mingea
			//fac random de numar pina la 11, ca sa aflu catre ce jucator sa mearga
			
			j2 = Math.floor((Math.random() * 10) + 1); 
			//if(x2[j2]>x2[j]) 
				dx = -dx;
			j1=j;
			x=x2[j1];
			y=y2[j1];
			imin=1;
			
		}
		if(imin==1) break;
	}
    //end lovire de jucator2
*/


//aici face partea finala din faza
//dupa ce se loveste de tz ori de jucatori, se trage la poarta
//in functie de faza, poate sa fie gol sau nu
//din fisier trebuie sa vina: cine ataca, ce rezultat are faza
	if(collision >= 7) {
		faracoliziuni = 1;
		var echipacareataca = 1;
		var gol=0;
		if(echipacareataca == 1) {
			//mingea trebuie sa mearga la dreapta (dx este pozitiv)
			if(dx<0) dx=-dx;
			if(gol == 0) {
				//aleg un punct in afara portii si duc minge la el
				//(800x500)
				var yPos1, xPos1;
				if(step == 0) {
					//salveaza pozitia actuala a mingii, ca sa generez dreapta
					yPos1 = y+10;
					xPos1 = x+10;

				}
				y += dy;
				//(500-yPos1)*(x-xPos1)/(800-xPos1)+yPos1;
				
				if(x>800) {
					faracoliziuni = 0;
					step = -1;
					x=0;
					y=0;
					collision=0;
					document.location.reload();
				}
				
				step++;
			}
		}
	}





	i++;
    x += dx;
	
	//y = (yPos2-yPos1)*(xPosNou-xPos1)/(xPos2-xPos1)+yPos1;
    //y = (y1[j2]-y1[j1])*(x-x1[j1])/(x1[j2]-x1[j1])+y1[j1];
    if(faracoliziuni == 0)
		y += dy;
	var deafisat = "j1="+j1 +" j2:"+ j2 + " x="+x+"; y="+y;
	ctx.fillText(deafisat,30,10);


}
setInterval(draw, 10);

</script>

</body>
</html>