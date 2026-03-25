<?php

$no_rubro = "91000";

    $sql="SELECT id_tasa, descrip, monto FROM tasas ORDER BY id_tasa";
    $result=pg_query($sql);			
		$i = $j = 0;	 
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
	     foreach ($line as $col_value) {	
          if ($i == 0) {
					  $id_tasa_lista[$j] = $col_value;
				  } elseif ($i == 1) {
					  $descrip_lista[$j] = utf8_decode ($col_value);											
				  } else {
					  $monto_lista[$j] = $col_value;	
            if ($id_tasa_lista[$j] < 10) {	 
	             $subnivel_temp = $no_rubro.".00".$id_tasa_lista[$j];
	          } elseif ($id_tasa_lista[$j] < 100) {	 
	             $subnivel_temp = $no_rubro.".0".$id_tasa_lista[$j];
	          } else {
	             $subnivel_temp = $no_rubro.".".$id_tasa_lista[$j];
	          }						
            $subniveles_lista[$j] = $subnivel_temp." &nbsp ".$descrip_lista[$j];											 
					 	$i = -1;
					}
					$i++;
			 }
			 $j++;
		}			
		$no_de_subniveles = $j;					 
    pg_free_result($result);	
?>