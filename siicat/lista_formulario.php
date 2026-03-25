<?php
echo "<tr>\n"; 	 
	echo "<td valign=\"center\" height=\"20\" colspan=\"2\">\n"; 
	echo "<table border=\"0\" width=\"100%\">\n";
		echo "<tr><td align=\"right\" colspan=\"4\" class=\"bodyText\"></td></tr>\n";
		echo "<tr><td align=\"center\" width=\"11%\" class=\"bodyTextH\">CODIGO: </td>\n";
		echo "<td align=\"left\" width=\"24%\" class=\"bodyTextD\">$cod_cat - $id_inmu</td>\n"; 
		echo "<td align=\"center\" width=\"12%\" class=\"bodyTextH\">DIRECCION: </td>\n";
		echo "<td align=\"left\" width=\"60%\" class=\"bodyTextD\">$direccion</td></tr>\n";	 
	echo "</table>\n";
	echo "</td>\n"; 

	echo "<td valign=\"center\" height=\"20\">\n";
	echo "<table border=\"0\" width=\"100%\">\n"; 
		echo "<tr><td align=\"right\" colspan=\"2\" class=\"bodyText\"></td></tr>\n";	 
		echo "<tr>\n";
		echo "<td align=\"center\" width=\"35%\" class=\"bodyTextH\">&nbsp REGIMEN: </td>\n";
		echo "<td align=\"left\" width=\"65%\" class=\"bodyTextD\">&nbsp $regimen</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
	echo "</td>\n";  
echo "</tr>\n"; 


echo "<tr>\n";
	echo "<td valign=\"top\" colspan=\"2\">\n";  	  	 	
	echo "<fieldset style=\"border-color: lightgrey; background-color:#EEEEEE;\"><legend></legend>\n";

	echo "<table border=\"0\" width=\"100%\">\n";	  	   
	echo "<tr>\n";
	echo "<td width=\"1%\"></td>\n";
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH_Small\">\n";
	echo "<a href=\"index.php?mod=5&inmu=$flecha_uv_ant&id=$session_id\"><input type=\"image\" name=\"V_ANT\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n";   	  	 
	echo "Dist.\n"; 
	echo "<a href=\"index.php?mod=5&inmu=$flecha_uv_post&id=$session_id\"><input type=\"image\" name=\"UV_POST\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n"; 	   		
	echo "</td>\n"; 
	echo "<td width=\"1%\"></td>\n";     	  
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH_Small\">\n";   
	echo "<a href=\"index.php?mod=5&inmu=$flecha_man_ant&id=$session_id\"><input type=\"image\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n";
	echo "Mz.\n";
	echo "<a href=\"index.php?mod=5&inmu=$flecha_man_post&id=$session_id\"><input type=\"image\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n"; 	 
	echo "</td>\n";		 
	echo "<td width=\"1%\"></td>\n";   	 
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH_Small\">\n"; 
	echo "<a href=\"index.php?mod=5&inmu=$flecha_pred_ant&id=$session_id\"><input type=\"image\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n";
	echo "Pred\n";
	echo "<a href=\"index.php?mod=5&inmu=$flecha_pred_post&id=$session_id\"><input type=\"image\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n"; 
	echo "</td>\n";	
	echo "<td width=\"1%\"></td>\n";  	   
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH_Small\">\n";  
	echo "<input type=\"image\" name=\"BLQ_ANT\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\">\n";  
	echo "Blq.\n";
	echo "<input type=\"image\" name=\"BLQ_POST\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\">\n"; 		 
	echo "</td>\n"; 	 
	echo "<td width=\"1%\"></td>\n"; 
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH_Small\">\n";  
	echo "<input type=\"image\" name=\"PISO_ANT\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\">\n"; 
	echo "Piso\n";
	echo "<input type=\"image\" name=\"PISO_POST\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\">\n"; 	 
	echo "</td>\n";
	echo "<td width=\"1%\"></td>\n"; 
	echo "<td align=\"center\" width=\"10%\" class=\"bodyTextH_Small\">\n";
	echo "<input type=\"image\" name=\"APTO_ANT\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\">\n"; 
	echo "Apto\n";
	echo "<input type=\"image\" name=\"APTO_POST\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\">\n"; 	 
	echo "</td>\n";	 
	echo "<td width=\"1%\"></td>\n"; 		   	 
	echo "<td align=\"center\" width=\"32%\" class=\"bodyTextH_Small\">\n"; 
	echo "<a href=\"index.php?mod=5&inmu=$flecha_inmu_ant&id=$session_id\"><input type=\"image\" src=\"http://$server/$folder/css/move_left.png\" alt=\"Anterior\" title=\"Anterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n";
	echo "Inmueble del Titular1\n";
	echo "<a href=\"index.php?mod=5&inmu=$flecha_inmu_post&id=$session_id\"><input type=\"image\" src=\"http://$server/$folder/css/move_right.png\" alt=\"Posterior\" title=\"Posterior\" width=\"9\" height=\"9\" border=\"0\"></a>\n"; 
	echo "</td>\n";	
	echo "<td width=\"1%\"></td>\n"; 		 	   	 	 	    
	echo "</tr>\n"; 
	echo "<tr>\n"; 
	echo "<td></td>\n";  	 
	echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_uv</td>\n";	  	  
	echo "<td></td>\n";
	echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_man</td>\n";	  
	echo "<td></td>\n";	 
	echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_pred</td>\n";	  
	echo "<td></td>\n";	 	
	echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_blq</td>\n";	  
	echo "<td></td>\n";	 
	echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_piso</td>\n";	  
	echo "<td></td>\n";	 
	echo "<td align=\"center\" class=\"bodyTextD_Small\">$cod_apto</td>\n";	  
	echo "<td></td>\n";	 	 		  
	echo "<td align=\"center\" class=\"bodyTextD_Small\">\n";
	echo "$pos_inmu/$check_inmuebles_tit1 </td>\n";
	echo "<td></td>\n";		 	 	
	echo "</tr>\n";	
	echo "</table>\n";  
	echo "</fieldset>\n";  	

	echo "<table border=\"0\" width=\"100%\">\n";	
	echo "<tr>\n";
		echo "<td colspan=\"9\" style='font-family: Arial; font-size: 1pt'>&nbsp</td>\n";		 
		echo "</tr>\n";	 	  	 
		echo "<tr>\n";
		echo "<td width=\"1%\">&nbsp</td>\n";	 	  	 
		echo "<td align=\"center\" width=\"21%\" class=\"bodyTextH\">Títular o Razón Social :</td>\n";
		echo "<td align=\"left\" width=\"45%\" class=\"bodyTextD\">&nbsp $titular1</td>\n";	
		echo "<td align=\"right\" width=\"4%\" class=\"bodyTextD\">\n";	
		if ($titular1 != "-") {
			echo "(<a href=\"index.php?mod=123&con=$tit_1id&id=$session_id\">ver</a>)&nbsp\n";
		}
		echo "</td>\n";		   	 
		echo "<td align=\"center\" width=\"8%\" class=\"bodyTextH\">Doc.:</td>\n"; 
		echo "<td align=\"left\" width=\"20%\" class=\"bodyTextD\">&nbsp $tit_1ci</td>\n"; 
		echo "<td width=\"1%\">&nbsp</td>\n";   #Col. 7	 	
		echo "</tr>\n";		 	 
		echo "<tr>\n";
		echo "<td>&nbsp</td>\n";   #Col. 1	 	  	 
		echo "<td align=\"center\" class=\"bodyTextH\">Segundo Títular :</td>\n"; 
		echo "<td align=\"left\" class=\"bodyTextD\">&nbsp $titular2</td>\n"; 	
		echo "<td align=\"right\" class=\"bodyTextD\">\n";
		if ($titular2 != "-") {
		    echo "(<a href=\"index.php?mod=123&con=$tit_2id&id=$session_id\">ver</a>)&nbsp\n";
		}
		echo "</td>\n";		 	   	 
		echo "<td align=\"center\" class=\"bodyTextH\">Doc.:</td>\n";
		echo "<td align=\"left\"class=\"bodyTextD\">&nbsp $tit_2ci</td>\n"; 	 
		echo "<td>&nbsp</td>\n";   #Col. 7	 	
	echo "</tr>\n";   
	echo "</table>\n";

	echo "<table border=\"0\" width=\"100%\">\n";	 	 
	echo "<tr>\n";
		echo "<td width=\"1%\" class=\"bodyText\"></td>\n";	 	  	 
		echo "<td align=\"center\" width=\"25%\" class=\"bodyTextH\">\n";
		echo "Modo Adquisición\n";   		   	 
		echo "</td>\n";
		echo "<td width=\"1%\"></td>\n";
		echo "<td align=\"center\" width=\"55%\" class=\"bodyTextH\">Documentación</td>\n";	 
		echo "<td width=\"1%\" class=\"bodyText\"></td>\n";
		echo "<td align=\"center\" width=\"16%\" class=\"bodyTextH\">Fecha Adq.</td>\n";    	 
		echo "<td width=\"1%\" class=\"bodyText\"></td>\n";	 
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td> &nbsp</td>\n";   #Col. 1	 		   	 
		echo "<td align=\"center\" class=\"bodyTextD\">$adq_modo_texto</td>\n"; 
		echo "<td> &nbsp</td>\n";   #Col. 3	
		echo "<td align=\"center\" class=\"bodyTextD\">$adq_doc_texto</td>\n";
		echo "<td> &nbsp</td>\n";   #Col. 5	
		echo "<td align=\"center\" class=\"bodyTextD\">$adq_fech_texto</td>\n"; 	   
		echo "<td> &nbsp</td>\n";
	echo "</tr>\n";	   

	echo "<tr>\n";
		echo "<td> &nbsp</td>\n";	 	  	 
		echo "<td align=\"center\" class=\"bodyTextH\">Titularidad</td>\n";
		echo "<td> &nbsp</td>\n";	
		echo "<td align=\"center\" class=\"bodyTextH\">Número de Registro en Derechos Reales</td>\n"; 
		echo "<td> &nbsp</td>\n";
		echo "<td align=\"center\" class=\"bodyTextH\">Fecha DDRR</td>\n";   	 
		echo "<td &nbsp></td>\n";		 
	echo "</tr>\n";

	echo "<tr>\n";
		echo "<td></td>\n";	 		   	 
		echo "<td align=\"center\" class=\"bodyTextD\">$tit_cara_texto</td>\n";
		echo "<td></td>\n";  
		echo "<td align=\"center\" class=\"bodyTextD\">$der_num_texto</td>\n"; 	  
		echo "<td></td>\n";  
		echo "<td align=\"center\" class=\"bodyTextD\">$der_fech_texto</td>\n";  
		echo "<td></td>\n";   
	echo "</tr>\n";

	echo "</table>\n";	
	echo "</td>\n";

	# MUESTRA PLANO 
	echo "<td align=\"right\" valign=\"center\">\n";	
	if ($predio_existe) {
		include "c:/apache/siicat/generar_plano_predio.php";
		echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/predios.map&program=http://$server/cgi-bin/mapserv.exe&zoomsize=2&layer=Predios&layer=Manzanos&layer=Calles&imgext=$xmin $ymin $xmax $ymax&imgxy=800+800&mapext=&savequery=false&zoomdir=1&mode=map&imgbox=&imgsize=1600+1600&mapsize=200+200\" id=\"content\" width=\"200px\" height=\"200px\" align=\"middle\" valign=\"center\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">";
	    echo "</iframe>\n";
	} else {
	echo "<img src=\"graphics/siicat_sin_geometria.png\" height=\"200\" width=\"200\" alt=\"Sin geometr�a\" title=\"Sin geometr�a\">\n";	 
	}	 
	echo "</td>\n";

	echo "<input name=\"cod_cat\" type=\"hidden\" class=\"smallText\" value=\"$cod_cat\" />\n"; 
	echo "<input name=\"cod_uv\" type=\"hidden\" class=\"smallText\" value=\"$cod_uv\" />\n";
	echo "<input name=\"cod_man\" type=\"hidden\" class=\"smallText\" value=\"$cod_man\" />\n";	 
	echo "<input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
	echo "<input name=\"Submit\" type=\"hidden\" class=\"smallText\" value=\"Lista\" />\n";
	
?>
