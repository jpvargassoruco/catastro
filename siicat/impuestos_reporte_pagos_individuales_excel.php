<?php

   ########################################
   #---------------- RUTA ----------------#
   ########################################
   $esc_path = pg_escape_string('C:/apache/htdocs/tmp/');
   $esc_ext = pg_escape_string('.xls');
   $reportfile = "reporte_".$tipo_reporte.$esc_ext;
   $filepath = $esc_path.$reportfile;
   ########################################
   #--------- CONTENIDO EXCEL ------------#
   ########################################		 
   $content = "SISTEMA INTEGRAL DE INGRESOS MUNICIPALES Y CATASTRO \n GOBIERNO MUN. AUTONOMO DE $municipio, DISTRITO $distrito\nFecha: $fecha2, Hora: $hora\nREPORTE: PAGOS INDIVIDUALES $titulo_reporte\nRANGO DE FECHA DEL $fecha_inicio AL $fecha_final\n\n#\tFecha\tLugar Pago\tNo. Boleta\tConcepto\tFolio\tMonto\n";
   if ($check_imp == 0) {
      $content = $content."\nNO HAY ENTRADAS REGISTRADAS EN EL RANGO DE FECHA SELECCIONADO\n";						 
   } else {
      $i = 0;
      while ($i < $no_de_impresiones) {
	       $j = $i + 1; 
   	     $content = $content."$j\t$fech_pago[$i]\t$nombre_banco[$i]\t$no_boleta_banco[$i]\t$concepto[$i]\t$folio[$i]\t$monto_banco[$i]\n";
         $i++;
      }
	    $content = $content."\n\t\t\t\t\tTotal Bs.\t$monto_total\n";			
   }
   ########################################
   #--------- ESCRIBIR ARCHIVO -----------#
   ########################################	
   if (!$handle = fopen($filepath, "w")) {
         $error = 2;
			   #exit; 
   }
   if (!fwrite($handle, $content)) {
         $error = 3; 
         #exit;
   }
   fclose($handle);
?>