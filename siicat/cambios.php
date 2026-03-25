<?php
$error = false;
########################################
#---------------- SUBMIT --------------#
########################################	
if (isset($_POST["submit"])) { 
   if ($_POST["submit"] == "Añadir") { 
      $cambio_id_select = ""; 
 		  $columnas = array('--DATOS PREDIO---','cod_cat','dir','ter_smen','-----TITULAR-----','cod_pad','tit1','tit_1ci','tit2','tit_pers','tit_cant',
			                  'tit_bene','tit_cara','--DOMICILIO TIT--','dom_dpto','dom_ciu','dom_dir','------DDRR-------','der_num','der_fech','---ADQUISICION---',
												'adq_modo','adq_doc','adq_fech','------VIA--------','via_tipo','via_clas','via_uso','via_mat','----SERVICIOS----','ser_alc',
												'ser_agu','ser_luz','ser_tel','ser_gas','ser_cab','--INST. ESPEC.---','ter_eesp','esp_aac','esp_tas',
												'esp_tae','esp_ser','esp_gar','esp_dep','-----MEJORAS-----','mej_lav','mej_par','mej_hor','mej_pis','mej_otr',
												'------OTROS------','ter_uso','ter_ace','ter_mur','ter_san');	
	    $no_de_variables = 55;	
			$i = 0;
	    while ($i < $no_de_variables) {
		     $texto = $columnas[$i];
			   $sql="SELECT num, permitido FROM info_permitido WHERE col_nombre = '$texto'";	
         $check_existence = pg_num_rows(pg_query($sql));
         if ($check_existence > 0 ) {	 
	          $result = pg_query($sql);
            $info_temp = pg_fetch_array($result, null, PGSQL_ASSOC);
				    $numero[$i] = $info_temp['num'];
            $cadena = $info_temp['permitido'];
						$j = $k = $x = 0;
            while ($j <= strlen($cadena)) {
               $char = substr($cadena, $j, 1);	
	             if ($char == ',') {
                  $valor1[$i][$x] = substr($cadena,$k,$j-$k);
									$valor2[$i][$x] = utf8_decode (abr($valor1[$i][$x]));	
		              $k = $j+1;	
									$x++;		
	             } elseif ($j == strlen($cadena)) {
                  $valor1[$i][$x] = substr($cadena,$k,$j-$k);	
									$valor2[$i][$x] = utf8_decode (abr($valor1[$i][$x]));										
		              $k = $j+1;	
									$x++;		
	             }							  
	             $j++;   
            } #END_OF_WHILE				    
			   } else {
				   $numero[$i] = 1;
					 $valor1[$i][0]= "";
					 $valor2[$i][0]= "-----";					 
			   }
			   $i++;
	    }	
	 } else $cambio_id_select = $_POST["cambio_id_select"]; 
}
########################################
#---------------- Añadir --------------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "Añadir")) { 
   $fecha_cambio_temp = $_POST["fecha_cambio"];
   $variable_temp = $_POST["variable"];	 
   $valor_ant_temp = trim ($_POST["valor_ant"]);
	 $stage2 = $_POST["stage2"];
	 if ($stage2 != "") {
	    $valor_ant_temp = $stage2;
	 }
   $sql="SELECT id FROM cambios WHERE fecha_cambio = '$fecha_cambio_temp' AND variable = '$variable_temp' AND id_inmu = '$id_inmu'";
   $check_cambios = pg_num_rows(pg_query($sql));	
	 if ($check_cambios > 0) {
	    $error = true;
			$fecha_cambio_temp = change_date ($fecha_cambio_temp);
			$mensaje_de_error = "Error: Ya existe un cambio de esa variable en fecha $fecha_cambio_temp!";
	 } else {  
      pg_query("INSERT INTO cambios (id_inmu, fecha_cambio, variable, valor_ant) 
			          VALUES ('$id_inmu','$fecha_cambio_temp','$variable_temp','$valor_ant_temp')");
	 }					
}
########################################
#---------------- BORRAR --------------#
########################################	
if ((isset($_POST["confirmar"])) AND ($_POST["confirmar"] == "SI")) { 
   $cambio_id_select = $_POST["cambio_id"]; 
	 pg_query("DELETE FROM cambios WHERE id = '$cambio_id_select' AND id_inmu = '$id_inmu'");
}
########################################
#-------- LEER CAMBIOS DE TABLA -------#
########################################	
$sql="SELECT id, fecha_cambio, variable, valor_ant FROM cambios WHERE id_inmu = '$id_inmu' ORDER BY fecha_cambio,id";
#$result = pg_query($sql);
#$info = pg_fetch_array($result, null, PGSQL_ASSOC);
$no_de_cambios = pg_num_rows(pg_query($sql));	
if ($no_de_cambios > 0) {
   $result = pg_query($sql); 
	 $i = $j = 0; 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
				 if ($i == 0) {
            $cambio_id[$j] = $col_value;
				 } elseif ($i == 1) {
            $fecha_cambio[$j] = change_date ($col_value);
				 } elseif ($i == 2) {
            $variable[$j] = abr($col_value);
				 } else {
            $valor_ant[$j] = utf8_decode (abr($col_value));
				    $i = -1;
				 }
			   $i++;
      }
			$j++;
   }										 
   pg_free_result($result);
} else {
   $mensaje_cambio = "No se ha registrado ningun cambio con ese $predio en la base de datos.";
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################	

	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas

	 if ($nivel > 1) {
	    echo "<form id=\"form1\" name=\"doublecombo\" method=\"post\" action=\"index.php?mod=5&id=$session_id#tab-8\" accept-charset=\"utf-8\">\n";	 	 
	 }
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	 echo "         <fieldset><legend>Historial del $predio</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 7 Columnas   
	 echo "               <tr>\n"; 
	 echo "                  <td width=\"5%\"></td>\n";   #Col. 1		  	                     
	 echo "                  <td align=\"center\" width=\"14%\" class=\"bodyTextH\">\n";   #Col. 2    	  	 
	 echo "                     FECHA\n"; 	   		
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 3	   
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">\n";   #Col. 4	  
	 echo "                     VARIABLE\n";	 
	 echo "                  </td>\n"; 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 5	 
   echo "                  <td align=\"center\" width=\"48%\" class=\"bodyTextH\">\n";   #Col. 6	
	 echo "                     VALOR ANTERIOR\n";
	 echo "                   </td>\n";
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	  	   		 	   	 	 	    
	 echo "               </tr>\n";
   if ($no_de_cambios > 0) {
	    $i = 0;
	    while ($i < $no_de_cambios) {
	       $j = $i-1;   
	       echo "               <tr>\n"; 
	       echo "                  <td>\n"; 
			   if ($nivel > 1) {
			      if (!isset($_POST["submit"])) {
		           if ($i == 0){
			            echo "                   <input name=\"cambio_id_select\" value=\"$cambio_id[$i]\" type=\"radio\" checked=\"checked\">\n";
		           } else {
			            echo "                   <input name=\"cambio_id_select\" value=\"$cambio_id[$i]\" type=\"radio\">\n";						 
			         }
			      } else {  
               if ($cambio_id_select == $cambio_id[$i]) { 
			            echo "                   <font color=\"red\" size=\"4\"> ></font>\n";	 
			         } else {
                  echo "                   &nbsp\n";	 
			         }
            }
			   }
			   echo "                  </td>\n";   #Col. 1	
         echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 2						
	       echo "                     $fecha_cambio[$i]\n";						 	                      		
	       echo "                  </td>\n"; 
	       echo "                  <td></td>\n";   #Col. 3				   
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 4  
			   $texto = utf8_decode (abr($variable[$i]));
	       echo "                     $texto\n";	 
	       echo "                  </td>\n";  
         echo "                  <td></td>\n";   #Col. 5				
	       echo "                  <td align=\"center\" class=\"bodyTextD_small\">\n";   #Col. 6   	 
	       echo "                     $valor_ant[$i]\n"; 	
	       echo "                  </td>\n";
         echo "                  <td></td>\n";   #Col. 7					 	   		 	   	 	 	    
	       echo "               </tr>\n";
			   $i++;
	    } 
   } else {
	    echo "               <tr>\n"; 
	    echo "                  <td align=\"center\" class=\"bodyTextD_small\" colspan=\"7\">$mensaje_cambio</td>\n";   #Col. 1-7			 
	    echo "               </tr>\n";
	 }
	 echo "            </table>\n";  
	 echo "         </fieldset>\n";
	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 if ($error) {
      echo "      <tr>\n"; 
	    echo "         <td align=\"center\" colspan=\"3\">\n"; #Col. 1-3
      echo "            <font color=\"red\">$mensaje_de_error</font>\n";	 
	    echo "         </td>\n";
	    echo "      </tr>\n";	   	 
	 }
	 if (($nivel > 1) AND ($activo == 1)) {
	    if (!isset($_POST["submit"])) {  
         echo "      <tr height=\"40px\">\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 1 	    
         echo "         <td align=\"center\" valign=\"center\">\n";   #Col. 2 
	       echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n";		 
	       echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";	
	       echo "            <input name=\"submit\" type=\"submit\" value=\"Añadir\" class=\"smallText\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";
				 if ($no_de_cambios > 0) {				   	                            
	          echo "            <input name=\"submit\" type=\"submit\" value=\"Borrar\" class=\"smallText\">\n";
				 }
         echo "         </td>\n";
	       echo "         <td> &nbsp</td>\n";   #Col. 3 			 
         echo "      </tr>\n";
			} else {	
			   if ($_POST["submit"] == "Añadir") { 
	          echo "      <tr>\n";  
	          echo "         <td> &nbsp</td>\n";   #Col. 1                       
	          echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	          echo "            <table border=\"0\" width=\"100%\">\n";  #TABLE 5 Columnas   				 
	          echo "               <tr>\n"; 
	          echo "                  <td width=\"5%\">\n"; #Col. 1	
            echo "                     &nbsp\n";	 
			      echo "                  </td>\n";   
            echo "                  <td width=\"15%\" align=\"center\">\n";   #Col. 2	
						echo "                     <label class=\"bodyTextH\">&nbsp&nbsp&nbsp&nbsp FECHA &nbsp&nbsp&nbsp&nbsp</label>\n";				
            echo "                     <input type=\"text\" class=\"navTextS\" name=\"fecha_cambio\" id=\"form_anadir1\" value=\"$fecha2\">\n";					 	                      		
	          echo "                  </td>\n"; 
#	          echo "                  <td width=\"1%\"></td>\n";   #Col. 				   
	          echo "                  <td width=\"40%\" align=\"center\" class=\"Text_small\">\n";   #Col. 3 
						echo "                     <label class=\"bodyTextH\">&nbsp&nbsp&nbsp&nbsp VARIABLE &nbsp&nbsp&nbsp&nbsp</label>\n";							 
            echo "                     <select class=\"navTextS\" name=\"variable\" size=\"1\" onChange=\"redirect(this.options.selectedIndex)\" >\n";
						$i = 0;
						while ($i < $no_de_variables) {
						   $texto = $columnas[$i];
							 $texto2 = utf8_decode (abr($columnas[$i]));
							 if ($texto == "tit1") {
	                echo "                        <option id=\"form0\" value=\"$texto\" selected=\"selected\"> Titular 1 o Razon Social</option>\n";							 
							 } else {
		              echo "                        <option id=\"form0\" value=\"$texto\"> $texto2</option>\n";
							 }	
							 $i++;						
						}																				
            echo "                     </select>\n";	
	          echo "                  </td>\n";  
#            echo "                  <td width=\"1%\"></td>\n";   #Col. 		
	          echo "                  <td width=\"30%\" align=\"left\" class=\"bodyTextD_small\">\n";   #Col. 4 
						echo "                     <label class=\"bodyTextH\">&nbsp&nbsp&nbsp&nbsp VALOR ANTERIOR &nbsp&nbsp&nbsp&nbsp</label>\n";							 	 
            echo "                     <select class=\"navTextS\" name=\"stage2\" size=\"1\">\n";
            echo "                        <option value=\"\" selected>-----</option>\n";						
            echo "                     </select>\n";							
	          echo "                  </td>\n";	
            echo "                  <td width=\"10%\"></td>\n";   #Col. 5							
	          echo "               </tr>\n";
	          echo "               <tr>\n";
            echo "                  <td colspan=\"3\"></td>\n";   #Col. 1-3							 													
	          echo "                  <td align=\"right\" class=\"bodyTextD_small\">\n";   #Col. 4 					 	 
            echo "                     <input type=\"text\" name=\"valor_ant\" class=\"navTextS\" id=\"form_anadir1\" value=\"\">\n";
	          echo "                  </td>\n";
            echo "                  <td></td>\n";   #Col. 5				 	   		 	   	 	 	    
	          echo "               </tr>\n";	
	          echo "            </table>\n";  
	          echo "         </td>\n";
	          echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	          echo "      </tr>\n";
?>		 
<script>
<!--						
/*
<!-- Autor des Skripts: Michael Mailer -->
<!-- Originalversion: http://www.webaid.de -->
<!-- Dieses Skript stammt vom JavaScript Einf�hrungskurs  -->
<!-- von Thomas Barmetler: ***** www.barmetler.de ***** -->
*/
  var groups=document.doublecombo.variable.options.length
  var group=new Array(groups)
  for (i=0; i<groups; i++)
    group[i]=new Array()
<?php
	 $i = 0;
   while ($i < $no_de_variables) {
	    $k = 0;	
			$no_de_valores = $numero[$i];
			while ($k < $no_de_valores) {	
			   $abrev = $valor1[$i][$k];
         $texto = $valor2[$i][$k];
			   echo "     group[$i][$k]=new Option(\"$texto\",\"$abrev\");\n";
				 $k++;
			}       
	    $i++;
	 }
?>

  var temp=document.doublecombo.stage2

  function redirect(x){
    for (m=temp.options.length-1;m>0;m--)
    temp.options[m]=null
    for (i=0;i<group[x].length;i++){
      temp.options[i]=new Option(group[x][i].text,group[x][i].value)
    }
    temp.options[0].selected=true
  }

  function go(){
    location=temp.options[temp.selectedIndex].value
  }

  // -->
</script>
<?php 												
			      echo "      <tr>\n";
	          echo "         <td> &nbsp</td>\n";   #Col. 1 				  
 	          echo "         <td align=\"center\">\n"; #Col. 2	
			      echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 		
	          echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";			 									 							
			      echo "            <input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Añadir\">&nbsp&nbsp&nbsp&nbsp&nbsp\n"; 
			      echo "            <input name=\"\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"No Añadir\">\n"; 								
	          echo "         </td>\n";	
 	          echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	          echo "      </tr>\n";															 			
			   } elseif ($_POST["submit"] == "Borrar") { 
			      echo "      <tr>\n";
	          echo "         <td> &nbsp</td>\n";   #Col. 1 				  
 	          echo "         <td align=\"center\">\n"; #Col. 2	
			      echo "            <font color=\"red\"> Est� seguro de borrar el cambio seleccionado?</font>\n"; 
			      echo "            <input name=\"id_inmu\" type=\"hidden\" value=\"$id_inmu\">\n"; 		
	          echo "            <input name=\"search_string\" type=\"hidden\" value=\"$search_string\">\n";
	          echo "            <input name=\"cambio_id\" type=\"hidden\" value=\"$cambio_id_select\">\n";				 									 							
			      echo "            <input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"SI\">\n"; 
 	          echo "            &nbsp&nbsp&nbsp&nbsp&nbsp<input name=\"confirmar\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"NO\">\n"; 			
	          echo "         </td>\n";	
 	          echo "         <td> &nbsp</td>\n";   #Col. 3	  	 
	          echo "      </tr>\n";
			   }	
	    } 
	 }  	  	 	 	 	  
	 echo "      </form>\n";		 
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
#	 echo "   <br />&nbsp;<br />\n";
#	 echo "</td>\n";	
	
?>