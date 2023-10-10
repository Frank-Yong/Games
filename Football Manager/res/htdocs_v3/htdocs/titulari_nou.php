<link type="text/css" href="resurse/jquery-ui-1.css" rel="stylesheet">
     
		
		<script type="text/javascript" src="resurse/jquery-1.js"></script>
		<script type="text/javascript" src="resurse/jquery-ui-1.js"></script>
        <script src="resurse/jquery_002.js"></script>
        <script src="resurse/jquery.js"></script>
   
        
        <script type="text/javascript">
			
			var myVar = {};
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
					//$(this).hide();
					$.ajax({
						type: "POST",
						url: "salveaza.php",
						data:
						{
							echipa: myVar,
						},
						success: function(msg){
							console.log(msg);
							//alert("PRIMUL 11 a fost salvat!");
							//window.location.replace("http://primul11.csmsiasi.ro/echipe/"+msg+"/");
						}
					});
				});

			});
			
			
		</script>
<link type="text/css" href="resurse/style.css" rel="stylesheet">
 
 <div>
            	<div style="padding-top: 23px; padding-left: 20px;">
                <div style="float:left; padding-left:10px;">
            		<div id="fb_logged"></div>
                    <div id="textAlege">Ai nevoie de inca 10 jucatori si un portar.</div>
                    <div id="salveaza" style="margin-left: -4px; margin-top: 3px;"><img src="resurse/salveaza.png" alt=""></div>
                </div>
                <div style="clear: both"></div>
                
                </div>
            </div>
        	<div id="column_left">
            <div style="padding-left: 38px; padding-top: 33px;">	
            	<div class="pos ui-droppable" id="gk">GK</div>
                
                <div class="clear"></div>
                
            	<div class="pos ui-droppable" id="dl">DL</div>
                <div class="pos ui-droppable" id="dc1">DC</div>
                <div class="pos ui-droppable" id="dc2">DC</div>
                <div class="pos ui-droppable" id="dc3">DC</div>
                <div class="pos ui-droppable" id="dr">DR</div>
                
                
                <div class="clear"></div>
                
                <div class="pos ui-droppable" id="ml">ML</div>
                <div class="pos ui-droppable" id="mc1">MC</div>
                <div class="pos ui-droppable" id="mc2">MC</div>
                <div class="pos ui-droppable" id="mc3">MC</div>
                <div class="pos ui-droppable" id="mr">MR</div>
                
                <div class="clear"></div>
                
                
                <div class="pos ui-droppable" id="fl">FL</div>
                <div class="pos ui-droppable" id="fc1">FC</div>
                <div class="pos ui-droppable" id="fc2">FC</div>
                <div class="pos ui-droppable" id="fc3">FC</div>
                <div class="pos ui-droppable" id="fr">FR</div>

            	<div class="clear"></div>
            </div>    
            </div>
            <div id="column_right">
            	<div style="height: 25px;">
                </div>
                             
<?php

	$sql = "SELECT p.* 
	        FROM user u 
			LEFT JOIN userplayer up
			ON up.UserID=u.id
			LEFT JOIN player p
			ON up.PlayerID=p.id 
			WHERE u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";
	$res = mysql_query($sql);

	$posanterior = "-";
	$contor = 0;
	mysql_data_seek($res,0);
	while($p_array = mysql_fetch_assoc($res)) {
		switch ($p_array['Position']) {
				case 1: $pos = "GK"; break;
				case 2: $pos = "DR"; break;
				case 3: $pos = "DC"; break;
				case 4: $pos = "DL"; break;
				case 5: $pos = "MR"; break;
				case 6: $pos = "MC"; break;
				case 7: $pos = "ML"; break;
				case 8: $pos = "FR"; break;
				case 9: $pos = "FC"; break;
				case 10: $pos = "FL"; break;
		}
		$contor++;
		if(substr($posanterior,0,1) != substr($pos,0,1) || $contor>5)
			{
				echo "<div class=\"clear\"></div>";
				$posanterior = $pos;
				$contor = 0;
			}
		$pid = $p_array['id'];
		$celulaid="d".$pid;

		
		?>
                <div class="juc_init">
				<div style="position: relative;" class="juc ui-draggable" id="<?php echo $pid; ?>">
					<?php echo "$pos: ".substr($p_array['fname'],0,1).'.'.$p_array['lname']; ?>
					
					<img src="images/missing_player_mic.jpg" width="55" height="57">
					
				</div>
				</div>

<?php
	}
mysql_free_result($res);
?>

<script>
		$('#gk').html($('#1').html());

</script>
</div>

