<?php

    $sql="SELECT id_rubro, clase, codigo, act_rub, descrip FROM patentes_rubro WHERE clase = '1' ORDER BY codigo, id_rubro ASC";
    $result=pg_query($sql);			
		$i = $j = 0;	 
    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {			 			           
	     foreach ($line as $col_value) {	
          if ($i == 0) {
					  $id_rubro_lista[$j] = $col_value;
				  } elseif ($i == 1) {
					  $clase = $col_value;	
				  } elseif ($i == 2) {
					  $codigo = $col_value;											
				  } elseif ($i == 3) {
					  $act_rub = utf8_decode ($col_value);
				  } else {
					  $descrip = utf8_decode ($col_value);
						$rubro[$j] = $codigo." ".$act_rub;												 
					 	$i = -1;
					}
					$i++;
			 }
			 $j++;
		}			
		$no_de_rubros = $j;					 
    pg_free_result($result);	
?>