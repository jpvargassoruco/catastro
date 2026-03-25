<?php   

################################################################################
#-------------------- EXTRAER COORDENADAS DE UNA LINEA-----------------------#
################################################################################

 $div_x = array();
 $div_y = array();
 $i = 0;
 $j = 0;
 $k = 0;
 $extract = "X";
 #$strlength = strlen ($div_intermed);
#echo "$strlength<br>";	
 $no_de_puntos_intermedios = 0;
 $char_ant = "";
 while ( ($i < strlen ($div_intermed)) AND (!$error) ) {
    $char = substr($div_intermed, $i, 1);
#echo "<br>El CHAR no.$i es un $char";			
		if ((check_numeros($char)) AND ($i != strlen ($div_intermed)-1)) {
		   $i++;
		} else {
       if (($extract == "X") AND (check_numeros($char_ant))) {
           $div_x[$k] = substr($div_intermed,$j,$i-$j);
			     $div_x[$k] = ROUND($div_x[$k],3);
#echo "DIV_X $k: $div_x[$k]<br>";						 
			     $j=$i+1;
					 $i++;
					 $extract = "Y";
       }	elseif (($extract == "Y") AND (check_numeros($char_ant)))  {
           $div_y[$k] = substr($div_intermed,$j,$i-$j);
			     $div_y[$k] = ROUND($div_y[$k],3);	
#echo "DIV_Y $k: $div_y[$k]<br>";					 	
					 if(!check_utm($div_x[$k],$div_y[$k])) {	
					    $error = true;
							$mensaje_de_error = "Error: Una coordenada se encuentra fuera de los limites permitidos!";
							$puntos_intermed = false;
					 } else {
#echo "DIV_X: $div_x[$k], DIV_Y: $div_y[$k]<br>";		
              $no_de_puntos_intermedios++;				 
					 }
					 $j=$i+1;	
					 $i++;	
			     $k++;
					 $extract = "X";
        } else {
				  $i++;
				}	
    }
		$char_ant = $char; 
 } #end_of_while
 
?>