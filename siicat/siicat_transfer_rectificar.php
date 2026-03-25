<?php
################################################################################
#                     EL SIGUIENTE CODIGO FUE CREADO POR:                      #
#         MERCATORGIS - SISTEMAS DE INFORMACION GEOGRAFICA Y DE CATASTRO       #
#     ***** http://www.mercatorgis.com  *****  info@mercatorgis.com *****      #
################################################################################

$id_transfer = $_POST["id_transfer"];

################################################################################
#-------------------------- SELECCION DE IMPUESTO -----------------------------#
################################################################################	
$calcular_transfer_urbano = $calcular_transfer_rural = false;	
if ((isset($_POST["id_inmu"])) OR (isset($_GET["inmu"]))) {
	 $calcular_transfer_urbano = true;
	 $concepto = "TRANSFER URBANO";
	 $id_item = $id_inmu;
	 $tabla_transfer = "transfer";
	 $tabla_imp_transfer = "imp_transfer";	 
	 include "siicat_info_inmu_leer_datos.php";
} elseif ((isset($_POST["id_predio_rural"])) OR (isset($_GET["idpr"]))) {
   $calcular_transfer_rural = true;
	 $concepto = "TRANSFER RURAL";
	 $id_item = $id_predio_rural;
	 $tabla_transfer = "transfer_rural";
	 $tabla_imp_transfer = "imp_transfer_rural";	 
	 include "siicat_info_predio_rural_leer_datos.php";
}

################################################################################
#------------------------------- RECTIFICACION --------------------------------#
################################################################################	
   
	 ### LEER DATOS DE TABLA TRANSFER
   $sql="SELECT tan_fech_ini, tan_fech_fin, tan_modo, tan_doc, tan_mont_usd, tan_mont_bs,
	              tan_cara, tan_1id, tan_2id, tan_der_fech, tan_der_num, tan_folio
         FROM $tabla_transfer WHERE id = '$id_transfer'";
   $check_transfer = pg_num_rows(pg_query($sql));
   if ($check_transfer == 1) {
	    $result = pg_query($sql);
      $info = pg_fetch_array($result, null, PGSQL_ASSOC);
      if (($info['tan_fech_ini'] == "1900-01-01") OR (trim($info['tan_fech_ini']) == "")) {
         $adq_fech_temp = "1900-01-01";
      } else $adq_fech_temp = $info['tan_fech_ini'];
      $tan_fech_fin =  $info['tan_fech_ini'];	 
      $adq_modo = $info['tan_modo'];
      $adq_doc = $info['tan_doc'];
	    $adq_doc_texto = utf8_decode($adq_doc);
      $adq_mont_usd = $info['tan_mont_usd'];
      $adq_mont_bs = $info['tan_mont_bs'];
      $tit_cara = $info['tan_cara'];
      $tit_1id = $info['tan_1id'];	 
      $tit_2id = $info['tan_2id'];
      if (($info['tan_der_fech'] == "1900-01-01") OR (trim($info['tan_der_fech']) == "")) {
         $der_fech_temp = "1900-01-01";
      } else $der_fech_temp = $info['tan_der_fech'];	
      $der_num = $info['tan_der_num'];
      $folio = $info['tan_folio'];			
	  	pg_free_result($result);
	    ### UPDATE TABLA INFO_INMU O INFO_PREDIO_RURAL 
			if ($calcular_transfer_urbano) { 
         $id_inmu_update = $id_inmu;
         include "siicat_info_inmu_update.php";
			} elseif ($calcular_transfer_rural) { 
         $id_predio_rural_update = $id_predio_rural;
         include "siicat_info_predio_rural_update.php";
			}		
	    ### BORRAR PROPIETARIOS ADICIONALES
			if ($calcular_transfer_urbano) {
	       pg_query("DELETE FROM info_inmu_contrib_add WHERE id_inmu = '$id_inmu'");	
			} elseif ($calcular_transfer_rural) {
	       pg_query("DELETE FROM info_predio_rural_contrib_add WHERE id_predio_rural = '$id_predio_rural'");				
			}	 
	    ### COPIAR PROPIETARIOS ADICIONALES
			if ($calcular_transfer_urbano) {				 
         $sql="INSERT INTO info_inmu_contrib_add SELECT cod_geo, id_inmu, tan_xid FROM transfer_contrib_add
	              WHERE id_inmu = '$id_inmu' AND tan_fech_ini = '$adq_fech_temp' AND tan_fech_fin = '$tan_fech_fin'";	
#echo "SQL: $sql <br />";
			} elseif ($calcular_transfer_rural) {
         $sql="INSERT INTO info_predio_rural_contrib_add SELECT id_predio_rural, tan_xid FROM transfer_rural_contrib_add
	              WHERE id_predio_rural = '$id_predio_rural' AND tan_fech_ini = '$adq_fech_temp' AND tan_fech_fin = '$tan_fech_fin'";				
			}
	    pg_query($sql);
      ### BORRAR REGISTROS TRANSFER	
			if ($calcular_transfer_urbano) {	
	       pg_query("DELETE FROM transfer WHERE id_inmu = '$id_inmu' AND id = '$id_transfer'");			 	 	 	 	 	  
	       pg_query("DELETE FROM transfer_contrib_add WHERE id_inmu = '$id_inmu' AND tan_fech_ini = '$adq_fech_temp'");
		  } elseif ($calcular_transfer_rural) {
	       pg_query("DELETE FROM transfer_rural WHERE id_predio_rural = '$id_predio_rural' AND id = '$id_transfer'");			 	 	 	 	 	  
	       pg_query("DELETE FROM transfer_rural_contrib_add WHERE id_predio_rural = '$id_predio_rural' AND tan_fech_ini = '$adq_fech_temp'");		
			}
			### BORRAR REGISTRO EN IMP_CONTROL_BANCO (SI EXISTE)
      $sql="DELETE FROM imp_control_banco WHERE concepto = '$concepto' AND id_item = '$id_item' AND folio = '$folio'";
			pg_query($sql);
			### MODIFICAR REGISTROS EN IMP_TRANSFER
      $sql="UPDATE $tabla_imp_transfer SET forma_pago = '', estatus = 'PRELIQUID', fech_pago = '1900-01-01', fech_reg = '1900-01-01', hora_reg = '', userid = '' WHERE folio = '$folio'";		
#echo "L60 SQL: $sql <br />";
			pg_query($sql);
			if ($calcular_transfer_urbano) {
         $sql="UPDATE imp_transfer SET estatus = 'PRELIQUID' WHERE estatus = 'OBSOLETO' AND cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
		  } elseif ($calcular_transfer_rural) {
         $sql="UPDATE imp_transfer_rural SET estatus = 'PRELIQUID' WHERE estatus = 'OBSOLETO' AND id_predio_rural = '$id_predio_rural'";
			}	 							 
#echo "L63 SQL: $sql <br />";			
			pg_query($sql);			
      ########################################
	    #-------------- REGISTRO --------------#
      ########################################
	    $accion = "Rectificado Transf.";
	    pg_query("INSERT INTO registro (userid, ip, fecha, hora, accion, valor) 
		         VALUES ('$user_id','$ip','$fecha','$hora','$accion','$cod_cat')");	
   }		  	 
?>
