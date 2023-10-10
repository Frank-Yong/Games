<?php

error_reporting(0);

class cls {
	private $LIGA;
	private $etapa;

	public function cls($liga) {
		$this->LIGA = $liga;
	}

	public function maxetapa() {
		$sql = "SELECT MAX(a.etapa) 
				FROM clasament a
				WHERE a.competitieId=".$this->LIGA;
		$res = mysql_query($sql);
		list($etapa) = mysql_fetch_row($res);
		mysql_free_result($res);

		$this->etapa = $etapa;
	}

	public function etape($etapa) {
		//preia etapa cea mai mare
		$this->maxetapa();

		//daca vine o etapa, sa afiseze etapa respectiva
		if($et>0) {
			$this->etapa=$et;
		} 
		if($this->etapa == 0) $myet = 1;
		else $myet=$this->etapa;
		echo "<h1>Etapa ".$myet."</h1>";

		$sql = "SELECT a.id, a.userId_1, a.userId_2, a.etapa, a.scor, a.datameci, b.TeamName, c.TeamName
				FROM invitatiemeci a
				LEFT JOIN user b
				on a.userId_1=b.id
				LEFT JOIN user c
				on a.userId_2=c.id
				WHERE a.accepted=1 and a.etapa=$myet and tipmeci=".$this->LIGA;
		$res = mysql_query($sql);
		?>
		<table id="rezultate" width="100%" cellpadding="1">
		<?php
		echo "<tr><th>Meci</th><th>Scor</th></tr>";
		while(list($id, $userId_1, $userId_2, $etapa, $scor, $datameci, $TeamName1, $TeamName2) = mysql_fetch_row($res)) {
			echo "<tr class=\"tr-1\"><td>$TeamName1 - $TeamName2</td><td>$scor</td></tr>";
		}
		?>
		</table>			
		<?php
		mysql_free_result($res);
	}

	public function clasament($et) 
	{

		echo "<h1>Clasament</h1>";
		//preia etapa cea mai mare
		$this->maxetapa();
	
		//daca vine o etapa, sa afiseze etapa respectiva
		if($et>0) {
			$this->etapa=$et;
		} 

				?>
		<table id="rezultate" width="100%" cellpadding="1">
					<tr>
						<th><font color="<?php echo $gk; ?>">Pos</font></th>
						<th><font color="<?php echo $df; ?>">Echipa</font></th>	
						<th><font color="<?php echo $gk; ?>">Meciuri</font></th>
						<th><font color="<?php echo $gk; ?>">Victorii</font></th>
						<th><font color="<?php echo $gk; ?>">Egaluri</font></th>
						<th><font color="<?php echo $gk; ?>">Infringeri</font></th>
						<th><font color="<?php echo $gk; ?>">Golaveraj</font></th>
						<th><font color="<?php echo $gk; ?>">Puncte</font></th>
					</tr>

		<?php

		$sql = "SELECT a.etapa, b.TeamName, c.nume, a.victorii, a.egaluri, a.infringeri, a.gm, a.gp, a.puncte
				FROM clasament a
				LEFT JOIN user b
				ON b.id=a.userId
				left join competitie c
				on a.competitieId=c.id
				WHERE a.etapa=".$this->etapa . "
				 ORDER BY a.puncte DESC, a.gm-a.gp DESC";
		//echo "$sql";
		$res = mysql_query($sql);
		$index=1;
		while(list($et, $echipa, $liga, $v,$e, $inf, $gm, $gp, $puncte) = mysql_fetch_row($res)) {
		?>
					<tr class="tr-1">
						<td><?php echo $index."."; ?></td>	
						<td><?php echo $echipa; ?></td>
						<td><?php echo $et; ?></td>
						<td><?php echo $v; ?></td>
						<td><?php echo $e; ?></td>
						<td><?php echo $inf; ?></td>
						<td><?php echo "$gm - $gp"; ?></td>
						<td><?php echo $puncte;	?></td>

					</tr>
		<?php
			$index++;
		}
		mysql_free_result($res);
		?>
		</table>
	<?php

	}



}


?>