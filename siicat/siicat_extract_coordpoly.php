<?php 
################################################################################
#-------------------- EXTRAER COORDENADAS DE UN POLIGONO-----------------------#
################################################################################

$p_x = $point_x = array();
$p_y = $point_y = array();
$i = 0;
$j = 15;
$x = 0; 
    while ($i <= strlen($coord_poly)) {
        $char = substr($coord_poly, $i, 1);
	      if ($char == ' ') {
           $p_x[$x] = substr($coord_poly,$j,$i-$j);
			     $point_x[$x] =ROUND($p_x[$x],3);
					 #echo $$p_x[$x]." ".$point_x[$x];
			     $j=$i+1;
	      }			
	      if ($char == ',') {
           $p_y[$x] = substr($coord_poly,$j,$i-$j);
			     $point_y[$x] =ROUND($p_y[$x],3);		
		       $j=$i+1;			
			     $x++;
	      } 
	      $i++;   
    } #end_of_while
?>
