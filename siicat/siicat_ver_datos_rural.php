<?php

include "siicat_info_predio_leer_datos.php";

$col_norte_nom = $col_norte_med = $col_sur_nom = $col_sur_med = "AAA";
$col_este_nom = $col_este_med = $col_oeste_nom = $col_oeste_med = "AAA";

#	 echo "      <tr>\n";                       
#	 echo "         <td valign=\"top\" height=\"180\" colspan=\"3\" class=\"bodyText\">\n";  #Col. 1+2+3	  
#	 echo "         <fieldset><legend>Datos del terreno</legend>\n";
/*
	 echo "            <table border=\"0\" width=\"800px\">\n";   # 8 Columnas
	 echo "               <tr>\n"; 	 
	 echo "                  <td valign=\"top\" height=\"40\" width=\"50%\">\n";   #Col. 1+2+3  
	 echo "                     <fieldset><legend>Datos del Predio</legend>\n";
	 echo "                     <table border=\"0\" width=\"100%\">\n"; #TABLE  2 Columnas	 
 	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Superficie</td>\n";
#	 echo "                           <td> &nbsp</td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Servicios Públicos</td>\n";
#	 echo "                           <td> &nbsp</td>\n";		 	
	 echo "                        </tr>\n";
 	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" width=\"25%\" class=\"bodyTextH_Small\">&nbsp Superf. s/mens:</td>\n";	 	
	 echo "                           <td align=\"left\" width=\"35%\" class=\"bodyTextD_Small\">&nbsp $ter_smen m²</td>\n";	
 	 echo "                           <td align=\"left\" width=\"25%\" class=\"bodyTextH_Small\">&nbsp Agua:</td>\n";	 	
	 echo "                           <td align=\"left\" width=\"15%\" class=\"bodyTextD_Small\">&nbsp $ser_agu_texto</td>\n";	                    
	 echo "                        </tr>\n";
 	 echo "                        <tr>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Superf. s/doc:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_sdoc_texto</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Luz:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_luz_texto</td>\n";
 	 echo "                        </tr>\n";	 
	 echo "                        <tr>\n"; 
	 echo "                           <td align=\"center\" colspan=\"2\">Información sobre la vía</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Telefono:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_tel_texto</td>\n";	
	 echo "                        </tr>\n";	
 	 echo "                        <tr>\n";	
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Material de vía:</td>\n"; 
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp $via_mat_texto</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Alcantarillado:</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_alc_texto</td>\n";  
	 echo "                        </tr>\n"; 
	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">&nbsp Topografía</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp TV Cable:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_cab_texto</td>\n"; 	
 	 echo "                        </tr>\n";
	 echo "                        <tr>\n";
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Topografía</td>\n";	 	 
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp $ter_topo_texto</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Gas Domiciliario:</td>\n";	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_gas_texto</td>\n";
 	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Forma</td>\n";	 	 
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp $ter_form_texto</td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Ubicación</td>\n";
 	 echo "                        </tr>\n";	 
	 echo "                           <td align=\"center\" colspan=\"2\">Información adicional</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Ubicación:</td>\n";	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_ubi_texto</td>\n"; 		 	  	 	 
	 echo "                        </tr>\n"; 
	 echo "                        <tr>\n";		 
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Destino de Uso:</td>\n";	
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp $ter_uso_texto</td>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp No. de Frentes:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_nofr_texto</td>\n";		 	  	 	 
	 echo "                        </tr>\n"; 
	 echo "                        <tr>\n";		 
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Edif. especiales:</td>\n";	 
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp $ter_eesp_texto</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Medida Frente:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_fren_texto</td>\n";	 	  	 	 
	 echo "                        </tr>\n";  	
	 echo "                        <tr>\n";		 
	 echo "                           <td class=\"bodyTextH_Small\">&nbsp Inst. Sanitaria:</td>\n"; 	 	 
	 echo "                           <td class=\"bodyTextD_Small\">&nbsp $ter_san_texto</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Medida Fondo:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_fond_texto</td>\n"; 	  	 	 
	 echo "                        </tr>\n";
	 echo "                        <tr>\n";		 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Muro perimetral:</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_mur_texto</td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">&nbsp </td>\n";	 	 
	 echo "                        </tr>\n";	 		  	 	   	 	 
	 echo "                     </table>\n";
	 echo "                     </fieldset>\n";
	 echo "                  </td>\n";
	 echo "                  <td valign=\"top\" height=\"40\" width=\"50%\">\n";   #Col. 1+2+3  
	 echo "                     <fieldset><legend>Datos del Inmueble</legend>\n";
	 echo "                     <table border=\"0\" width=\"100%\">\n"; #TABLE  2 Columnas	
 	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Superficie</td>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Instalaciones Especiales</td>\n"; 	
	 echo "                        </tr>\n";	 
	 echo "                        <tr>\n";	  
 	 echo "                           <td align=\"left\" width=\"30%\" class=\"bodyTextH_Small\">&nbsp Superficie s/mens:</td>\n";	 	
	 echo "                           <td align=\"left\" width=\"25%\" class=\"bodyTextD_Small\">&nbsp $ter_smen m²</td>\n";
	 echo "                           <td align=\"left\" width=\"35%\" class=\"bodyTextH_Small\">&nbsp Aire Acondicionado:</td>\n";
	 echo "                           <td align=\"left\" width=\"10%\" class=\"bodyTextD_Small\">&nbsp $esp_aac_texto</td>\n";	                
	 echo "                        </tr>\n";
 	 echo "                        <tr>\n";	 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Superficie s/doc:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ter_sdoc_texto</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Tanque Subterraneo:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $esp_tas_texto</td>\n";
 	 echo "                        </tr>\n";	 
	 echo "                        <tr>\n"; 
	 echo "                           <td align=\"center\" colspan=\"2\">Valoración Catastral</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Tanque Elevado:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $esp_tae_texto</td>\n";	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n"; 
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Zona Homogénea:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ben_zona</td>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Area de Servicio:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $esp_ser_texto</td>\n";	
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td align=\"center\" colspan=\"2\">Conexión a servicios públicos</td>\n";	
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Garaje:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $esp_gar_texto</td>\n";			 	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Agua:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_agu_texto</td>\n";	  
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Depositos:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $esp_dep_texto</td>\n";		 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";	
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Luz:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_luz_texto</td>\n";	  
	 echo "                           <td align=\"center\" colspan=\"2\">Mejoras</td>\n";		 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Telefono:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_tel_texto</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Lavanderia</td>\n";		
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $mej_lav_texto</td>\n";	 	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td align=\"left\" width=\"15%\" class=\"bodyTextH_Small\">&nbsp Alcantarillado:</td>\n";
	 echo "                           <td align=\"left\" width=\"15%\" class=\"bodyTextD_Small\">&nbsp $ser_alc_texto</td>\n";	
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Parrillero:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $mej_par_texto</td>\n";	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
 	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp TV Cable:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_cab_texto</td>\n"; 
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Horno:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $mej_hor_texto</td>\n"; 	 
	 echo "                        </tr>\n";	
	 echo "                        <tr>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Gas Domiciliario:</td>\n";
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $ser_gas_texto</td>\n";	 
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Piscina:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $mej_pis_texto</td>\n";  	 
	 echo "                        </tr>\n";
	 echo "                        <tr>\n";
	 echo "                           <td colspan=\"2\"> &nbsp</td>\n";		 
	 echo "                           <td align=\"left\" class=\"bodyTextH_Small\">&nbsp Otros:</td>\n";	 	
	 echo "                           <td align=\"left\" class=\"bodyTextD_Small\">&nbsp $mej_otr_texto</td>\n";  	 
	 echo "                        </tr>\n";	 	 	 	 	 
	 echo "                     </table>\n";
	 echo "                     </fieldset>\n";
	 echo "                  </td>\n";	 
	 echo "               </tr>\n";	 	 	 	 
	 echo "            </table>\n";	 */
 echo "            <table border=\"0\" width=\"800px\">\n";   # 8 Columnas
/*	 	 echo "               <tr>\n"; 
	 echo "         <td valign=\"top\" height=\"40\" colspan=\"2\">\n";   #Col. 1+2+3  
	 echo "         <fieldset><legend>Colindantes</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";                     #TABLE  10 Columnas
	 echo "               <tr>\n";
	 echo "                  <td align=\"right\" colspan=\"13\" class=\"bodyText\"></td>\n";   #Col. 1	 
	 echo "               </tr>\n";	   
	 echo "               <tr>\n";  	                     
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 1	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">NORTE:</td>\n";   #Col. 2	  	  	 
	 echo "                  <td align=\"center\" width=\"60%\" class=\"smallText\"><input type=\"text\" name=\"col_norte_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_norte_nom\"></td>\n"; #Col. 3
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 4	
	 echo "                  <td align=\"center\" width=\"6%\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 5	   
	 echo "                  <td align=\"center\" width=\"25%\" class=\"bodyTextD\"><input type=\"text\" name=\"col_norte_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_norte_med\"></td>\n";   #Col. 6	  	 
	 echo "                  <td width=\"1%\"></td>\n";   #Col. 7	
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">SUR:</td>\n";   #Col. 8	  	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_sur_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_sur_nom\"></td>\n"; #Col. 9
	 echo "                  <td></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 11   
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_sur_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_sur_med\"></td>\n";   #Col. 12 
	 echo "                  <td></td>\n";   #Col. 13	 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">ESTE:</td>\n";   #Col. 8	  	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_este_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_este_nom\"></td>\n"; #Col. 9
	 echo "                  <td></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 11   
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_este_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_este_med\"></td>\n";   #Col. 12 
	 echo "                  <td></td>\n";   #Col. 13	 	 	 	    
	 echo "               </tr>\n";
	 echo "               <tr>\n";  	                     
	 echo "                  <td></td>\n";   #Col. 1		 	 
	 echo "                  <td align=\"center\" class=\"bodyTextH\">OESTE:</td>\n";   #Col. 8	  	  	 
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_oeste_nom\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col\" value=\"$col_oeste_nom\"></td>\n"; #Col. 9
	 echo "                  <td></td>\n";   #Col. 10	
	 echo "                  <td align=\"center\" class=\"bodyTextH\">Mts.</td>\n";   #Col. 11   
	 echo "                  <td align=\"center\" class=\"bodyTextD\"><input type=\"text\" name=\"col_oeste_med\" id=\"form_anadir1\" class=\"navText\" maxlength=\"$max_strlen_col_med\" value=\"$col_oeste_med\"></td>\n";   #Col. 12 
	 echo "                  <td></td>\n";   #Col. 13	 	 	 	    
	 echo "               </tr>\n";	 
	 echo "            </table>\n";  
	 echo "         </fieldset>\n";	 	 
	 echo "         </td>\n"; 
	 echo "      </tr>\n"; */
	 echo "               <tr height=\"20\">\n";	
	 echo "                  <td width=\"1%\">&nbsp </td>\n";	 	 
	 echo "                  <td width=\"12%\" align=\"left\" valign=\"top\"><b>&nbsp Observaciones:</b></td>\n";	
	 echo "                  <td width=\"87%\" align=\"left\" class=\"bodyTextD_Small\">&nbsp $ctr_obs_texto</td>\n"; 		 	  	 	 
	 echo "               </tr>\n"; 	 	     		  
	 echo "            </table>\n"; 
#	 echo "         </fieldset>\n";
#	 echo "         </td>\n";	
#	 echo "      </tr>\n";
?>