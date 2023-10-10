<link type="text/css" href="resurse/jquery-ui-1.css" rel="stylesheet">
<link type="text/css" href="resurse/prettyPhoto.css" rel="stylesheet">
		<script src="resurse/all.js" async="" id="facebook-jssdk"></script><script src="resurse/ga.js" async="" type="text/javascript"></script><script type="text/javascript" src="resurse/jquery-1.js"></script>
		<script type="text/javascript" src="resurse/jquery-ui-1.js"></script>
        <script src="resurse/jquery_002.js"></script>
        <script src="resurse/jquery.js"></script>
        
        <!--CUFON-->
		<script src="resurse/cufon-yui.js" type="text/javascript"></script><style type="text/css">cufon{text-indent:0!important;}@media screen,projection{cufon{display:inline!important;display:inline-block!important;position:relative!important;vertical-align:middle!important;font-size:1px!important;line-height:1px!important;}cufon cufontext{display:-moz-inline-box!important;display:inline-block!important;width:0!important;height:0!important;overflow:hidden!important;text-indent:-10000in!important;}cufon canvas{position:relative!important;}}@media print{cufon{padding:0!important;}cufon canvas{display:none!important;}}</style>
        <script src="resurse/Oswald_700.js" type="text/javascript"></script>
        <script type="text/javascript">
            Cufon.replace('.tabelMeci', {textShadow: '2px 2px rgba(0,0,0,.90)'});
        </script>
        <!-- /CUFON-->
        
        <script type="text/javascript">
			
			var myVar = {};
			var utilizatorLogat = "";
			 var numeUtilizatorLogat = "";
			 var pozaUtilizatorLogat = "";
			 var echipaCompleta = "";
	 
			$(function(){
				$( ".juc" ).draggable({ 
					snap: ".pos",
					snapTolerance: 10,
					snapMode: "inner",
					stack: "div",
					revert: function (event, ui) {
						//overwrite original position
						$(this).data("draggable").originalPosition = {
							top: 0,
							left: 0
						};
						//return boolean
						return !event;
					},
					start:function(event, ui){
						
						var offset = $(this).offset();
            			var xPos = offset.left;
            			var yPos = offset.top;
						
						//alert("X: "+xPos+" / Y: "+yPos);
						
						//alert($(this).css("top"));
						//alert($(this).css("left"));
						//myVar[$(this).attr('id')] = "";
						//alert(myVar[$(this).attr('id')]);
						ui.helper.data('from-id-x',  xPos);
						ui.helper.data('from-id-y',  yPos);
						//alert("JUCATOR: "+$(this).attr('id'));
					},
					stop:function(event, ui){
						//alert(utilizatorLogat+"!");
						alegePortar = true;
						 jucAlesi = 0;
						  for(var i in myVar)
							 {
								 if((myVar[i]!="")&&(myVar[i]!="gk"))
									jucAlesi++;
								 //alert(i);
								 //alert($(this).attr('id'));
								 if(myVar[i]=="gk")
									alegePortar = false;
							 }
						 
						 jucatoriDeAles = 10-jucAlesi;
						 
						 if(alegePortar==true)
                         {
							if(jucatoriDeAles>0) 
						 		textPortar = " si un portar";
						 	else
								textPortar = " un portar";
						 }
						 else
						 	textPortar = "";
						 
						 if(jucatoriDeAles==1)
						 	jucAles = " jucator ";
						 else
						 	jucAles = " jucatori ";
						 
						 echipaCompleta = false;
						 
						 if(jucatoriDeAles>0) 
						 {
						 	$('#textAlege').html("Alege "+jucatoriDeAles+jucAles+" de camp"+ textPortar +".");
						 }
						 else if(alegePortar==true)
						 {
							 $('#textAlege').html("Alege "+ textPortar +".");
						 }
						 else
						 {
							 $('#textAlege').html("ECHIPA ESTE COMPLETA!");
							 echipaCompleta = true;
						 }
						 
						 if((echipaCompleta)&&(utilizatorLogat!=""))
						 {
							 $("#salveaza").fadeIn();
						 }
						 else
						 	  $("#salveaza").hide();
						 
					}
				});
				
				$('.pos').droppable({
					tolerance: 'fit',       
					out: function(event, ui) {
						$(ui.helper).mouseup(function() {
							myVar[ui.draggable.attr('id')] = "";
						});
					},
					over: function(event, ui) {
						$(ui.helper).unbind("mouseup");
					},
					drop:function(event, ui){
					
					var inlocuire = false;	
					var estePortar = false;
					var nrJuc = 0;	
						 for(var i in myVar)
						 {
							 if(myVar[i]!="")
							 	nrJuc++;
							 //alert(i);
							 //alert($(this).attr('id'));
							 if(myVar[i]=="gk")
							 	estePortar = true;
								
							 if(myVar[i]==$(this).attr('id'))
							 {
								 var inlocuire = true;
								 //alert("EXISTA DEJA UN JUCATOR IN ACEA POZITIE! "+i+" TREBUIE MUTAT LA: "+myVar[ui.draggable.attr('id')]);					
								//alert(myVar[ui.draggable.attr('id')]);	
								 if((myVar[ui.draggable.attr('id')]=="")||(myVar[ui.draggable.attr('id')]==undefined))
								 {
									$("#"+i).css({
									"left": "0px",
									"top": "0px"
									})
									 myVar[i] = "";	
									 //alert("Schimbat cu rezerva");
								 }
								 else
								 {
								 	$("#"+i).offset({ top: ui.draggable.data('from-id-y'), left: ui.draggable.data('from-id-x')});
								 	 myVar[i] = myVar[ui.draggable.attr('id')];
									 //alert("Interschimbat pozitii");
								 }
							 }
						 }	
						
						//alert(estePortar);
						
						if((myVar[ui.draggable.attr('id')]!="")&&(myVar[ui.draggable.attr('id')]!=undefined))
							inlocuire = true;
						
						//alert(myVar[ui.draggable.attr('id')]);
						//alert(inlocuire);
						//alert(estePortar);
						
						//alert("JUCATOR: "+ui.draggable.attr('id')+" / POZITIE ACTUALA: "+$(this).attr('id')+" / POZITIE VECHE: "+myVar[ui.draggable.attr('id')]);
						
						 if(!inlocuire)
						 {
							 if((nrJuc>10)||((nrJuc>9)&&(!estePortar)&&($(this).attr('id')!="gk")))
							 {
								 
								 if(!estePortar)
								 	alert("TREBUIE SELECTAT PORTARUL!");
								 else
								 	alert("ECHIPA ESTE COMPLETA!"); 
								 
								 jQuery("#"+ui.draggable.attr('id')).css({
									"left": "0px",
									"top": "0px"
								})
							 }
							 else
							 	myVar[ui.draggable.attr('id')] = $(this).attr('id');
						 }
						 else
						 {
							 myVar[ui.draggable.attr('id')] = $(this).attr('id');
						 }
						 	
						
						 //myVar[ui.draggable.attr('id')] = $(this).attr('id');  
						 
						console.log(myVar);
						 
						 
						
						 //alert( 'I was dragged from ' + ui.draggable.data('from-id') );
					  }
				});
				
				$("#salveaza").click(function (){
					$(this).hide();
					$.ajax({
						type: "POST",
						url: "salveaza.php",
						data:
						{
							echipa: myVar,
							utilizator: utilizatorLogat,
							numeUtilizator: numeUtilizatorLogat,
							pozaUtilizator: pozaUtilizatorLogat,
							idMeci: '82'
						},
						success: function(msg){
							console.log(msg);
							alert("PRIMUL 11 a fost salvat!");
							window.location.replace("http://primul11.csmsiasi.ro/echipe/"+msg+"/");
						}
					});
				});

			});
			
			
		</script>
<link type="text/css" href="resurse/style.css" rel="stylesheet">

<script type="text/javascript">
	/*$(document).ready(function() {
                $("#hidden_link").prettyPhoto().trigger('click');
	});*/
</script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25945563-1']);
  _gaq.push(['_setDomainName', '.csmsiasi.ro']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>    
	<div id="content">

    
    		<div id="macheta">
            
            </div>
 
 <div id="albastru">
            	<div style="padding-top: 23px; padding-left: 20px;">
                <div style="float:left; padding-left:10px;">
            		<div id="fb_logged"></div>
                    <div id="textAlege">Alege 10 jucatori de camp si un portar.</div>
                    <div id="salveaza" style="margin-left: -4px; margin-top: 3px; display:none;"><img src="resurse/salveaza.png" alt=""></div>
                </div>
                <div style="clear: both"></div>
                
                </div>
            </div>
        	<div id="column_left">
            <div style="padding-left: 38px; padding-top: 112px;">	
                <div class="pos ui-droppable" id="fl"></div>
                <div class="pos ui-droppable" id="fc1"></div>
                <div class="pos ui-droppable" id="fc2"></div>
                <div class="pos ui-droppable" id="fc3"></div>
                <div class="pos ui-droppable" id="fr"></div>
                
                <div class="clear"></div>
                
                <div style="height: 60px;"></div>
                
                <div class="pos ui-droppable" id="ml"></div>
                <div class="pos ui-droppable" id="mc1"></div>
                <div class="pos ui-droppable" id="mc2"></div>
                <div class="pos ui-droppable" id="mc3"></div>
                <div class="pos ui-droppable" id="mr"></div>
                
                <div class="clear"></div>
                <div style="height: 60px;"></div>
                
            	<div class="pos ui-droppable" id="dl"></div>
                <div class="pos ui-droppable" id="dc1"></div>
                <div class="pos ui-droppable" id="dc2"></div>
                <div class="pos ui-droppable" id="dc3"></div>
                <div class="pos ui-droppable" id="dr"></div>
                
                <div class="clear"></div>
                
                <div style="height: 17px;"></div>
                
            	<div class="pos ui-droppable" id="gk"></div>
            	<div class="clear"></div>
            </div>    
            </div>
            <div id="column_right">
            	<div style="height: 255px;">
                </div>
                             

				
                
                <div class="juc_init">
				<div style="position: relative;" class="juc ui-draggable" id="j967">
					<img src="resurse/phpThumb_005.jpg" alt="Gomo ONDUKU" title="Gomo ONDUKU">
				</div>
				</div>
				<div class="juc_init">
				<div style="position: relative;" class="juc ui-draggable" id="j667">
					<img src="resurse/phpThumb_008.jpg" alt="Leonard DOBRE" title="Leonard DOBRE">
				</div>
				</div>
					
					<div class="clear"></div>
					<div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j964"><img src="resurse/phpThumb_025.jpg" alt="Valentin DIMA" title="Valentin DIMA"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j99"><img src="resurse/phpThumb_027.jpg" alt="Alexandru CREȚU" title="Alexandru CREȚU"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j660"><img src="resurse/phpThumb_024.jpg" alt="Marius MIHALACHE" title="Marius MIHALACHE"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j797"><img src="resurse/phpThumb_015.jpg" alt="Gabriel BOSOI" title="Gabriel BOSOI"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j668"><img src="resurse/phpThumb_018.jpg" alt="Bogdan VIȘA" title="Bogdan VIȘA"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j186"><img src="resurse/phpThumb_012.jpg" alt="Iulian-Adrian VLADU" title="Iulian-Adrian VLADU"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j367"><img src="resurse/phpThumb_010.jpg" alt="Milan MITIC" title="Milan MITIC"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j920"><img src="resurse/phpThumb_019.jpg" alt="Ionuț VOICU" title="Ionuț VOICU"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j916"><img src="resurse/phpThumb_016.jpg" alt="Cristian MUNTEANU" title="Cristian MUNTEANU"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j847"><img src="resurse/phpThumb_026.jpg" alt="Florin PLĂMADĂ" title="Florin PLĂMADĂ"></div></div><div class="clear"></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j796"><img src="resurse/phpThumb_022.jpg" alt="Alessandro CAPARCO" title="Alessandro CAPARCO"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j915"><img src="resurse/phpThumb_007.jpg" alt="Branko GRAHOVAC" title="Branko GRAHOVAC"></div></div><div class="juc_init"><div style="position: relative;" class="juc ui-draggable" id="j919"><img src="resurse/phpThumb_004.jpg" alt="Nicușor GRECU" title="Nicușor GRECU"></div></div>
					
					<div class="clear"></div>                
               
                
            </div>
            <div class="clear"></div>
     </div>


</body></html>