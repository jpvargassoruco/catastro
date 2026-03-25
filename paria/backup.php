<?php

if (isset($_GET["mod"])) {   
	 $mod = $_GET["mod"];
}	else $mod = "";

########################################
#-------- OBTENER FECHA Y HORA --------#
########################################		
 $date = getdate(); 
 $dia_actual = $date['mday'];
 $mes_actual = $date['mon'];  
 $ano_actual = $date['year']; 
 if ($date['hours'] == 0) {
    $hours = 23;
		$dia_actual = $dia_actual-1;
		if ($dia_actual == 0) {
		   if (($mes_actual == 5) OR ($mes_actual == 7) OR ($mes_actual == 10) OR ($mes_actual == 12)) {
			    $dia_actual = 30;  
			 } elseif ($mes_actual == 3) {
			    $dia_actual = 28;
			 } else {
			    $dia_actual = 31;
			 }
		}
 } else {
    $hours = $date['hours']-1;
 }
 # Ajustar números con 1 cifra
 if ($dia_actual < 10) {
    $dia_actual = "0".$dia_actual;
 }
 if ($mes_actual < 10) {
    $mes_actual = "0".$mes_actual;
 } 
 $fecha = $ano_actual."-".$mes_actual."-".$dia_actual;

########################################
#------ CHEQUEAR ARCHIVO BACKUP -------#
########################################			
	 $backup_path = "c:/apache2/siicat/backup/siicat_$fecha.bkp";
	 $i = 1;
	 $archivo_existe = true;
	 while($archivo_existe) {
      if (file_exists($backup_path)) { 
         $archivo_existe = true;
				 $backup_path = "c:/apache2/siicat/backup/siicat_$fecha($i).bkp";
				 $bkpfecha = "$fecha($i)";
				 $i++;
      } else $archivo_existe = false;	   						 
	 }		 
 
########################################
#----------- DOWNLOADFILES ------------#
########################################	 
 
if ($mod == "") {
   $downloadfile = "http://localhost/tmp/siicat_$fecha.bkp";
   $filename = "siicat_$fecha.bkp";
#$filesize = filesize($downloadfile);

   header("Content-Type: text/bkp");
   header("Content-Disposition: attachment; filename=$filename");
#header("Content-Length: $filesize");

   readfile($downloadfile);
} elseif ($mod == "docs") {
   $downloadfile = "http://localhost/tmp/bkp_docu_".$fecha.".rar";
   $filename = "bkp_docu_".$fecha.".rar";
#$filesize = filesize($downloadfile);

   header("Content-Type: text/rar");
   header("Content-Disposition: attachment; filename=$filename");
#header("Content-Length: $filesize");

   readfile($downloadfile);
} elseif ($mod == "fotos") {
   $downloadfile = "http://localhost/tmp/bkp_foto_".$fecha.".rar";
   $filename = "bkp_foto_".$fecha.".rar";
#$filesize = filesize($downloadfile);

   header("Content-Type: text/rar");
   header("Content-Disposition: attachment; filename=$filename");
#header("Content-Length: $filesize");

   readfile($downloadfile);
}
exit;
?>