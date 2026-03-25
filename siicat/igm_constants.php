<?php
    
 # IP DEL SERVIDOR
 $server = "192.168.0.150";
  
 # CONEXION CON LA BASE DE DATOS
 $db_passw = "qwert";$db_name = "vallegrande"; $db_user = "postgres";	
 
 $dbconn = pg_connect("host=$server dbname=$db_name user=$db_user password=$db_passw")
 or die('<br>NO se pude conectar a la base de datos! <br>Verifique que PostgreSQL esté funcionando 
      como servicio de Windows y que la IP de la computadora esté registrada en el archivo ../apache/data/pg_hba.conf ' . pg_last_error());	 
 
 # IP DEL VISITANTE
 $ip = $_SERVER['REMOTE_ADDR']; 
 
 # AJUSTAR FECHA Y HORA
 $date = getdate();
 $dia_actual = $date['mday'];
 $mes_actual = $date['mon'];  
 $ano_actual = $date['year'];
 $ano_actua2 = substr($ano_actual,-2); 
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
			 if ($mes_actual == 1) {
			    $mes_actual = 12;
			 } else $mes_actual = $mes_actual - 1;
		}
#echo "dia $dia_actual, mes $mes_actual<br>";		
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
 $fecha2 = $dia_actual."/".$mes_actual."/".$ano_actual; 
 
 if ($hours < 10) {
    $hours = "0".$hours;
 }
 $minutes = $date['minutes'];
 if ($minutes < 10) {
    $minutes = "0".$minutes;
 } 
 $seconds = $date['seconds'];
 if ($seconds < 10) {
    $seconds = "0".$seconds;
 } 
 $hora = $hours.":".$minutes.":".$seconds;
 /*
 $pageview_new = $date['0'];
 $expiration_time = 1000; 
	*/
 # AÑO Y MES EN QUE SE CAMBIA DEL SIIM AL SISTEMA DE CATASTRO 
 $ano_cambio_de_sistema = 2017;
 $mes_cambio_de_sistema = 6;
 
 # ULTIMO ANO QUE APARECE PARA COBRAR
 if ($mes_actual > 12) {
   $ult_ano = $ano_actual-7; 
 } else $ult_ano = $ano_actual-7;  
 
 # NOMENCLATURA
 $Predio = "Predio";
 $predio = "predio";

 # COPYRIGHT 
 $copyright ="igm"; 
 
 # CENTRO DEL MAPA
 $centro_del_mapa_x = ($maximo_permitido_x - $minimo_permitido_x)/2 + $minimo_permitido_x;  
 $centro_del_mapa_y = ($maximo_permitido_y - $minimo_permitido_y)/2 + $minimo_permitido_y;  
  
 # ESCAPES (REEMPLAZOS)
 $esc1 = pg_escape_string('SRID=-1;POINT(');
 $esc2 = pg_escape_string('SRID=-1;MULTILINESTRING((');
 $esc3 = pg_escape_string('))'); 
 $esc4 = pg_escape_string('SRID=-1;MULTIPOLYGON(((');
 $esc5 = pg_escape_string(')))'); 
 
 # MANEJO DE ERRORES
 ini_set("display_errors", 0); 
 ini_set("log_errors", 0);
 ini_set("error_log", "C:/apache/siicat/log/errorlog.txt");
 ini_set("error_prepend_string", "Ha ocurrido un error en el programa. Pongase en contacto con el administrador del sistema!"); 
 ini_set("error_append_string", "");
 
?>