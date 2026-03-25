<?php
### LEER DATOS DE AUTORIDADES ###
    
 # IP DEL SERVIDOR
 $server = "127.0.0.1";
  
 # CONEXION CON LA BASE DE DATOS
 $db_passw = "qwert";
 $db_name = "vallegrande"; 
 $db_user = "postgres";	
  $dbconn = pg_connect("host=$server dbname=$db_name user=$db_user password=$db_passw")
 or die('<br>NO se pude conectar a la base de datos! <br>Verifique que PostgreSQL esté funcionando 
      como servicio de Windows y que la IP de la computadora esté registrada en el archivo ../apache/data/pg_hba.conf ' . pg_last_error());
# AJUSTAR FECHA Y HORA
$date = getdate();
$dia_actual = $date['mday'];
$mes_actual = $date['mon'];  
$ano_actual = $date['year']; 

$sql="SELECT puecod, pueges, puenom, pueres, pueabr FROM autoridades WHERE pueges = '$ano_actual'  ORDER BY puecod";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$puenom = 'Dra. Mariyela Soruco Peña';
	$pueres = 'Lic. Lorenzo Cabrera';
	$pueabr = 'Agrim. Ramiro Jordán Peña';	
} else {	
	$result=pg_query($sql);
	$i = $j = 0;
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
		foreach ($line as $col_value) {
			$puesto[$i][$j] = $col_value; 
			echo "$i $j $col_value<br />";	
			$j++; 
		}
	$j=0;
	$i++;
	echo "<br />";		
    }
}	


?>
