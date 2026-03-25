<?php  
	 include "c:/apache/siicat/siicat_generar_query.php"; 
	 $zoom = false;
   if ((isset($_POST["cod_cat"])) OR (isset($_GET["cod_cat"]))) {	
	    $zoom = true;
			if (isset($_POST["cod_cat"])) {
	       $cod_cat = $_POST["cod_cat"];
			} else {
	       $cod_cat = $_GET["cod_cat"];			
			}	
			if ($cod_cat == "-") {
			   $cod_uv = $_POST["cod_uv"];
				 $cod_man = $_POST["cod_man"];
         $result2=pg_query("SELECT (extent3d(the_geom)) FROM manzanos WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man'");
			} elseif ($cod_cat != "") {			
         $result2=pg_query("SELECT (extent3d(the_geom)) FROM predios WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'");	
			} else {			
         $result2=pg_query("SELECT (extent3d(the_geom)) FROM predios WHERE cod_uv = '1' AND cod_man = '1' AND cod_pred = '1'");		 		
			}
      $str_extent = pg_fetch_array($result2, null, PGSQL_ASSOC);
      $extent = $str_extent['extent3d'];
      ##########################################################################
      #--------------------- EXTRAER COORDENADAS DE BOX2D ---------------------#
      ##########################################################################		
      $xt_x = $xtent_x = array();
      $xt_y = $xtent_y = array();
      $x =0;
      $z = 0;
      $i =0;
      $j = 6; 
      while ($i <= strlen($extent)) {
         $char = substr($extent, $i, 1);
	       if (($char == ' ') AND ($z == 0)) {
            $xt_x[$x] = substr($extent,$j,$i-$j);
			      $xtent_x[$x] =ROUND($xt_x[$x],3);
			      $j=$i+1;
			      $z = 1;
echo "EXTENT_X[$x] es $xtent_x[$x]<br /> ";
	       } else if (($char == ' ') AND ($z == 1)) {
            $xt_y[$x] = substr($extent,$j,$i-$j);
			      $xtent_y[$x] =ROUND($xt_y[$x],3);		
		        $j=$i+3;			
			      $z = 0;
#echo "EXTENT_Y[$x] es $xtent_y[$x]<br /> ";
			      $x++;
	       } 
	       $i++;   
      }
      ##########################################################################
      #-------------------- CALCULAR CENTRO DEL MAPA --------------------------#
      ##########################################################################	
      $centerx = ($xtent_x[0] + $xtent_x[1])/2;
      $centery = ($xtent_y[0] + $xtent_y[1])/2;	    
      if ($centerx-$xtent_x[0] > $centery-$xtent_y[0]) {     # 1/2 EXTENSION DEL PREDIO REAL
         $predio_xtent_real = ($xtent_x[1] - $xtent_x[0])/2; 
#echo "PREDIO_XTENT_REAL viene de X y es $predio_xtent_real<br />\n";
      } else {
         $predio_xtent_real = ($xtent_y[1] - $xtent_y[0])/2;
#echo "PREDIO_XTENT_REAL viene de Y y es $predio_xtent_real<br />\n";
      }
			if ($cod_cat == "") {  # En caso que objeto es Linea o Punto o para Modificar Geometria
			   $objeto = $_POST["object"];
			   $centerx = $_POST["map_center_x"];
				 $centery = $_POST["map_center_y"];
		#		 if ((isset($_POST["submit"])) AND ($_POST["submit"] == "Modificar Objetos en el Mapa")) {		
				 if ((isset($_POST["accion"])) AND ($_POST["accion"] == "Modificar/Borrar")) {			 
				    $predio_xtent_real = 850;
						if ($objeto == "Calle") {
						   $layers = "layer=Manzanos&layer=Calles";
						} else {
						   $layers = "layer=Manzanos&layer=Lineas";
						}
				 } elseif ((isset($_POST['reemplazar'])) OR ($objeto == "Zona") OR ($objeto == "Uso de Suelo") OR ($objeto == "Material de Via")) {
				    $predio_xtent_real = 850;
						if ($objeto == "Predio") {
						   $layers = "layer=Predios&layer=Lineas";
						} elseif ($objeto == "Calle") {
						   $layers = "layer=Manzanos&layer=Calles";
						} elseif (($objeto == "Medidor de agua") OR ($objeto == "Antena") OR ($objeto == "Cabina Tel.") OR ($objeto == "Poste Electr.") OR ($objeto == "Horno") OR ($objeto == "Noria") OR ($objeto == "Letrina") OR ($objeto == "Medidor Viento") OR ($objeto == "Punto Control")) {
						   $layers = "layer=Manzanos&layer=Elementos especiales";
						}	elseif ($objeto == "Zona") {
						   $layers = "layer=Lineas&layer=Zonas homogeneas";
						}	elseif ($objeto == "Uso de Suelo") {
						   $layers = "layer=Lineas&layer=Uso de Suelo";
						}	elseif ($objeto == "Material de Via") {
						   $layers = "layer=Predios&layer=Material de Via";							 							 
						}	else {
						   $layers = "layer=Manzanos&layer=Lineas";
						}		 
				 } else {
				    $predio_xtent_real = 40;
						$layers = "layer=Predios&layer=Manzanos&layer=Lineas&layer=Calles&layer=Elementos especiales";
				 }
			} else {
			   $layers = "layer=Predios&layer=Manzanos&layer=Lineas&layer=Calles&layer=Elementos especiales";
			} 
      $predio_xtent_plano = 12;                     
      $xmin = $centerx - $predio_xtent_real * 16.1/$predio_xtent_plano;    
      $xmax = $centerx + $predio_xtent_real * 18.8/$predio_xtent_plano;
      $ymin = $centery - $predio_xtent_real * 10.3/$predio_xtent_plano;
      $ymax = $centery + $predio_xtent_real * 9.6/$predio_xtent_plano;
      include "c:/apache/siicat/siicat_generar_mapfile_zoom.php";
      include "c:/apache/siicat/siicat_generar_htmlfile_zoom.php";			
      include "c:/apache/siicat/siicat_generar_query_calles.php";			
      include "c:/apache/siicat/siicat_generar_query_objetos.php";
   } else {
      include "c:/apache/siicat/siicat_generar_mapfile.php";
   }		 
   echo "<td>\n";
   echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"1000px\" height=\"460px\">\n";   
   echo "      <tr>\n";
   echo "         <td valign=\"top\">\n";  
   if ($zoom) {	
	    echo "   <iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_zoom.map&$layers&zoomsize=2&mode=browse&program=c:/apache/cgi-bin/mapserv.exe'\" id=\"content\" width=\"918px\" height=\"727px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";	 	
	 } else {
      echo "   <iframe frameborder=\"0\" name=\"mapserver\" src=\"http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat.map&layer=Manzanos&layer=Lineas&layer=Predios&zoomsize=2&program=c:/apache/cgi-bin/mapserv.exe'\" id=\"content\" width=\"918px\" height=\"727px\" align=\"left\" scrolling=\"no\" noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";#

	 }
	 echo "            </iframe>\n";	
   echo "         </td>\n";	 
   echo "      </tr>\n";	 		
	 echo "   </table>\n";
	 echo "</td>\n";
 	 		
?>

