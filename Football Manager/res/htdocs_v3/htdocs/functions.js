function show_content(curent,total,id_caseta)
{
	for(i=1;i<=total;i++)
	{
		document.getElementById("tab_"+i+"_"+id_caseta).className = "tab-2";
		document.getElementById("tab_"+i+"_left_"+id_caseta).className = "tab-2-l";
		document.getElementById("tab_"+i+"_right_"+id_caseta).className = "tab-2-r";
		document.getElementById("container_"+i+"_"+id_caseta).style.display = "none";
	}
	document.getElementById("tab_"+curent+"_"+id_caseta).className = "tab-1";
	document.getElementById("tab_"+curent+"_left_"+id_caseta).className = "tab-1-l";
	document.getElementById("tab_"+curent+"_right_"+id_caseta).className = "tab-1-r";
	document.getElementById("container_"+curent+"_"+id_caseta).style.display = "";
}

function show_team(curent,total,id_caseta)
{
	for(i=1;i<=total;i++)
	{
		document.getElementById("container_"+i+"_"+id_caseta).style.display = "none";
	}
	document.getElementById("container_"+curent+"_"+id_caseta).style.display = "";
}


function ColorButton(curent,total,id_caseta)
{

	for(i=1;i<=total;i++)
	{
		document.getElementById("meniu_"+i+"_"+id_caseta).className = "tab-2";
		document.getElementById("meniu_"+i+"_"+id_caseta+"_left").className = "tab-2-l";
		document.getElementById("meniu_"+i+"_"+id_caseta+"_right").className = "tab-2-r";
	}
	
//	document.getElementById("submenu_"+curent+"_"+id_caseta).style.display = "none";
//	SwitchMenu('submenu_2_1');

	document.getElementById("meniu_"+curent+"_"+id_caseta).className = "tab-3";
	document.getElementById("meniu_"+curent+"_"+id_caseta+"_left").className = "tab-3-l";
	document.getElementById("meniu_"+curent+"_"+id_caseta+"_right").className = "tab-3-r";
}


function MenuButton(curent,total,id_caseta)
{

	for(i=1;i<=total;i++)
	{
		document.getElementById("meniu_"+i+"_"+id_caseta).className = "tab-2";
		document.getElementById("meniu_"+i+"_"+id_caseta+"_left").className = "tab-2-l";
		document.getElementById("meniu_"+i+"_"+id_caseta+"_right").className = "tab-2-r";
	}
	
	document.getElementById("meniu_"+curent+"_"+id_caseta).className = "tab-3";
	document.getElementById("meniu_"+curent+"_"+id_caseta+"_left").className = "tab-3-l";
	document.getElementById("meniu_"+curent+"_"+id_caseta+"_right").className = "tab-3-r";
}



function showComment()
{
	if (document.getElementById("comentariu").style.display == "")
		document.getElementById("comentariu").style.display = "none";
	else 
		document.getElementById("comentariu").style.display = "";
}
