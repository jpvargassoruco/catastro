<?php

#	 $cod_cat = $_POST["cod_cat"];	
  # $dos_resultados = false;
   ########################################
	 #           CODIGO CATASTRAL           #
	 ########################################	  
	 if ((isset($_POST["CAT_ANT_x"])) OR (isset($_POST["CAT_POST_x"]))){ 
      $sql="SELECT cod_uv,cod_man,cod_lote,cod_subl FROM info_predio WHERE cod_geo = '$cod_geo' ORDER BY cod_uv, cod_man, cod_lote, cod_subl";
	 }
   ########################################
	 #           PADRON MUNICIPAL           #
	 ########################################	  
	 #if ((isset($_POST["PAD_ANT_x"])) OR (isset($_POST["PAD_POST_x"]))){ 
	 #   $cod_pad = $_POST["cod_pad"];	 
   #   $sql="SELECT cod_uv, cod_man, cod_lote, cod_subl FROM info_predio ORDER BY cod_pad";
	 #}	
   ########################################
	 #                  U.V.                #
	 ########################################	  
	 if ((isset($_POST["UV_ANT_x"])) OR (isset($_POST["UV_POST_x"]))){ 
	    #$dos_resultados = true;
	    #$cod_uv = $_POST["cod_uv"];	
	   # $cod_man = "";		 
      $sql="SELECT DISTINCT ON (cod_uv) cod_uv, cod_man, cod_lote, cod_subl FROM info_predio WHERE cod_geo = '$cod_geo' ORDER BY cod_uv, cod_man, cod_lote, cod_subl";
			#WAS PASSIERT WENN ES NUR 1 UNTERSCHIEDLICHEN UV GIBT?						 		
	 }
   ########################################
	 #                  MANZANO             #
	 ########################################	  
	 if ((isset($_POST["MAN_ANT_x"])) OR (isset($_POST["MAN_POST_x"]))){ 
      $sql="SELECT DISTINCT ON (cod_man) cod_uv, cod_man, cod_lote, cod_subl FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' ORDER BY cod_man, cod_lote, cod_subl";
	 }	 	
   ########################################
	 #--------------- LOTE -----------------#
	 ########################################	  
	 if ((isset($_POST["LOTE_ANT_x"])) OR (isset($_POST["LOTE_POST_x"]))){    
      $sql="SELECT DISTINCT ON (cod_lote) cod_uv, cod_man, cod_lote, cod_subl FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' ORDER BY cod_lote, cod_subl";	
	 }
   ########################################
	 #------------- SUB-LOTE ---------------#
	 ########################################	  
	 if ((isset($_POST["SUBL_ANT_x"])) OR (isset($_POST["SUBL_POST_x"]))){    
      $sql="SELECT DISTINCT ON (cod_subl) cod_uv, cod_man, cod_lote, cod_subl FROM info_predio WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_lote = '$cod_lote' ORDER BY cod_subl";	
	 }		 		 	   
	 ########################################
	 #               APELLIDO               #
	 ########################################	 
	 #if ((isset($_POST["TIT_ANT_x"])) OR (isset($_POST["TIT_POST_x"]))){
	 #   $tit_1pat = $_POST["tit_1pat"];	 
  #    $sql="SELECT cod_uv, cod_man, cod_lote, cod_subl FROM info_predio ORDER BY tit_1pat, tit_1mat, tit_1nom1, tit_1nom2";
	 #}
	 ########################################
	 #               UBICACION              #
	 ########################################	 
	# if ((isset($_POST["DIR_ANT_x"])) OR (isset($_POST["DIR_POST_x"]))){
	#    $dir_nom = $_POST["dir_nom"];	 
  #    $sql="SELECT cod_uv, cod_man, cod_lote, cod_subl FROM info_predio ORDER BY dir_nom, dir_num, dir_bloq, dir_piso, dir_apto";
	# }	 	 
	 ########################################
	 #               SELECCIONAR            #
	 ########################################	
	 $result = pg_query($sql); 	  	 
	 $i = $j = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {      
				 foreach ($line as $col_value) {
				    if  ($j == 0) {
						   $cod_uv_list = $col_value;
				    } elseif  ($j == 1) {
						   $cod_man_list = $col_value;	
				    } elseif  ($j == 2) {
						   $cod_lote_list = $col_value;
						} else {
							 $cod_subl_list = $col_value;	 						 						 
				       $codigo[$i] = get_codcat ($cod_uv_list,$cod_man_list,$cod_lote_list,$cod_subl_list);								          
						   if ($codigo[$i] == $cod_cat) {
						      $position = $i;
               }
							 $i++;	
							 $j = -1;
						}
	          $j++;												      
				 }	 
      } # END_OF_WHILE
	 #}
#echo "POSITION: $position <br />\n";				
	 if (isset($_POST["CAT_ANT_x"]) OR isset($_POST["PAD_ANT_x"]) OR isset($_POST["UV_ANT_x"]) OR isset($_POST["MAN_ANT_x"]) OR isset($_POST["LOTE_ANT_x"]) OR isset($_POST["SUBL_ANT_x"])) {
			   if ($position == 0) {
 			      $cod_cat = $codigo[$i-1];
				 } else $cod_cat = $codigo[$position-1];
	 }
	 if (isset($_POST["CAT_POST_x"]) OR isset($_POST["PAD_POST_x"]) OR isset($_POST["UV_POST_x"]) OR isset($_POST["MAN_POST_x"]) OR isset($_POST["LOTE_POST_x"]) OR isset($_POST["SUBL_POST_x"])) {
			   if ($position == $i-1) {
 			      $cod_cat = $codigo[0];
				 } else $cod_cat = $codigo[$position+1];
	 }
?>