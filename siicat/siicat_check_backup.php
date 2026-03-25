<?php

$timestamp = strtotime($fecha.' - 7 days');
$fecha_1sem_atras = date('Y-m-d', $timestamp);
#$fecha = $fecha2;
#$menos_1_dia = true;   

$sql = "SELECT fecha FROM registro WHERE fecha > '$fecha_1sem_atras' AND accion = 'Backup Base de Datos' ORDER BY fecha DESC LIMIT 1";
$no_de_registros = pg_num_rows(pg_query($sql));
if ($no_de_registros == 0) {
   $check_backup = false;
	 $fecha_bkp = "---";
} else {
   $check_backup = true;
   $result_bkp = pg_query($sql);
   $info_bkp = pg_fetch_array($result_bkp, null, PGSQL_ASSOC);
   $fecha_bkp = $info_bkp['fecha'];
	 $fecha_bkp = change_date ($fecha_bkp);
   pg_free_result($result_bkp);	 
}

?>
