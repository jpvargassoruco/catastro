<?php
# DESCUENTO DE INTERESES
################################################################################
#------------------------- INFORMACION DE LA EDIFICACION-----------------------#
################################################################################ 
$sql="SELECT edi_num, edi_piso, edi_tipo, edi_ano, edi_edo 
      FROM info_edif 
      WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_ano <= '$gestion'
      ORDER BY edi_num, edi_piso";
      
$no_de_edificaciones = pg_num_rows(pg_query($sql));
$result=pg_query($sql);
$i = $j = 0;
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value) {
       if ($i == 0) {
            $edi_num[$j] = $col_value;
         } elseif ($i == 1) {
            $edi_piso[$j] = $col_value;
         } elseif ($i == 2) {
            $edi_tipo[$j] = abr($col_value);
         } elseif ($i == 3) {
            $edi_ano[$j] = $col_value;
            $ant = $ano_actual - $edi_ano[$j];
            if (($ant >= 0) AND ($ant <= 5)) {
             $antiguedad[$j] = "0 - 5 años";
            } elseif (($ant >= 6) AND ($ant <= 10)) {
             $antiguedad[$j] = "6 - 10 años";
            } elseif (($ant >= 11) AND ($ant <= 15)) {
             $antiguedad[$j] = "11 - 15 años";
            } elseif (($ant >= 16) AND ($ant <= 20)) {
             $antiguedad[$j] = "16 - 20 años";
            } elseif (($ant >= 21) AND ($ant <= 25)) {
             $antiguedad[$j] = "21 - 25 años";
            } elseif (($ant >= 26) AND ($ant <= 30)) {
             $antiguedad[$j] = "26 - 30 años";
            } elseif (($ant >= 31) AND ($ant <= 35)) {
             $antiguedad[$j] = "31 - 35 años";
            } elseif (($ant >= 36) AND ($ant <= 40)) {
             $antiguedad[$j] = "36 - 40 años";
            } elseif (($ant >= 41) AND ($ant <= 45)) {
             $antiguedad[$j] = "41 - 45 años";
            } elseif (($ant >= 46) AND ($ant <= 50)) {
             $antiguedad[$j] = "46 - 50 años";
            } else {
             $antiguedad[$j] = "> 51 años";
            } 
         } else {
            $edi_edo[$j] = abr($col_value);
             $i = -1;
             $j++;
         }
         $i++;
   }
}
########################################
#----- CALCULAR AREA EDIFICACIONES ----#
########################################
$sql="SELECT edi_num,edi_piso 
      FROM info_edif 
      WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'  AND edi_ano <= '$gestion'
      ORDER BY edi_num, edi_piso";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
    $edi_area = 0;
} else {
   $edi_area = 0;
   $result=pg_query($sql);
   $i = $j = 0;
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {                             
      foreach ($line as $col_value) {
         if ($i == 0) {
            $edi_num_temp = $col_value;
         } else {
            $edi_piso_temp = $col_value;
            $sql2="SELECT area(the_geom) FROM edificaciones WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred' AND edi_num = '$edi_num_temp' AND edi_piso = '$edi_piso_temp'";
            $check2 = pg_num_rows(pg_query($sql2));
         if ($check2 == 0) {
            $edi_area = $edi_area + 0;
            $area_edif[$j] = 0;
         } else {
            $result2=pg_query($sql2);
            $value = pg_fetch_array($result2, null, PGSQL_ASSOC);                     
            $area_edif[$j]= ROUND($value['area'],2); 
            pg_free_result($result2);
            $edi_area = $edi_area + $area_edif[$j];
         }
         $i = -1;
         }
         $i++;
      }
      $j++;
   }
}
################################################################################
#------------------------ VALORACION DE EDIFICACIONES -------------------------#
################################################################################       
$gestion_actual = $ano_actual-1;
$sql="SELECT * 
      FROM info_edif 
      WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'  AND edi_ano <= '$gestion' 
      ORDER BY edi_num, edi_piso ASC";
$no_de_edificaciones = pg_num_rows(pg_query($sql));
if ($no_de_edificaciones > 0) {
   $i = $k = 0;
   $result_edif = pg_query($sql);
   $savaluo = 0;
   while ($line = pg_fetch_array($result_edif, null, PGSQL_ASSOC)) { 
      $line_value[$i] = $no_de_objetos_validos[$i] = 0;                                  
      foreach ($line as $col_value) {
         $column_edif = get_column_edif ($k);
         $sql="SELECT valuacion FROM imp_valua_viv_materiales WHERE tipo = '$column_edif' AND material = '$col_value'";           
         $check_valua = pg_num_rows(pg_query($sql));
         if ($check_valua > 0) {
            $result_valua = pg_query($sql);
            $info_valua = pg_fetch_array($result_valua, null, PGSQL_ASSOC);                  
            $valua_temp = trim($info_valua['valuacion']);
            if ($valua_temp == "margin") {                   
               $line_value[$i] = $line_value[$i] + 1;
            } elseif ($valua_temp == "mecono") {                   
               $line_value[$i] = $line_value[$i] + 2;
            } elseif ($valua_temp == "econo") {                    
               $line_value[$i] = $line_value[$i] + 3;
            } elseif ($valua_temp == "bueno") {                 
               $line_value[$i] = $line_value[$i] + 4;
            } elseif ($valua_temp == "mbueno") {                      
               $line_value[$i] = $line_value[$i] + 5;
            } elseif ($valua_temp == "lujoso") {
               $line_value[$i] = $line_value[$i] + 6;
            }
            $no_de_objetos_validos[$i]++;                                                           
         }                                                                                                        
         $k++; 
      }   
      $line_media[$i] = ROUND($line_value[$i]/$no_de_objetos_validos[$i],2); 
      if ($line_media[$i] < 1.5) {
            $clase[$i] = "Marginal";
         } elseif ($line_media[$i] < 2.5) {
            $clase[$i] = "Muy Económico";
         } elseif ($line_media[$i] < 3.5) {
            $clase[$i] = "Económico";
         } elseif ($line_media[$i] < 4.5) {
            $clase[$i] = "Bueno";
         } elseif ($line_media[$i] < 5.5) {
            $clase[$i] = "Muy Bueno";
         }   else {
            $clase[$i] = "Lujoso";
         }    

      ########################################
      #----------- CALCULAR VALOR -----------#
      ########################################  

      $calidad_const[$i] = imp_calidad_const($gestion,$line_media[$i]);    
      if ($calidad_const[$i] == 0) {
         $avaluo_edif_separado[$i] = "-";
      }  else {
         $factor_deprec[$i] = imp_factor_deprec($gestion_actual,$edi_ano[$i],$ano_actual);   
         $avaluo_edif_separado[$i] = avaluo_const($calidad_const[$i], $area_edif[$i], $factor_deprec[$i]);  
         $avaluo_const_actual = $avaluo_const_actual + $avaluo_edif_separado[$i];      
      }

ROUND($value['area'],2);

      $avaluo[$i] = round($factor_deprec[$i]*$calidad_const[$i]*$area_edif[$i],2);
      $savaluo = $savaluo + $avaluo[$i];

      $k = 0;
      $i++;          
   }
}


$servicios_letras = "Servicios Basicos:&nbspMinimo SI &nbsp&nbsp&nbsp  Agua:".$ser_agu." &nbsp&nbsp&nbsp  Electricidad:";
$servicios_letras = $servicios_letras.$ser_luz."  &nbsp&nbsp&nbsp&nbsp Alcantarillado:".$ser_alc."  &nbsp&nbsp&nbsp&nbspTelefono:".$ser_luz;
$LineaDiv = str_repeat("-", 505);
$pmcCeros = str_pad($pmc, 6, "0", STR_PAD_LEFT);
$id_inmuCeros = str_pad($id_inmu, 6, "0", STR_PAD_LEFT);
$fecha_actual = date("d-m-Y h:i");
$metro2 = "m²";
$tit_col4="Año de Contr.";
$tit_col9="Valuación";
$via_mat_texto = trim(ucwords(strtolower($via_mat)));
   ########################################
   #----- FACTOR DEL MATERIAL DE VIA -----#
   ########################################
   if ($via_mat_texto == "Tierra"){
      $fact_via = 1;
   } elseif ($via_mat_texto == "Ripio") {
      $fact_via = 1.05;
   } elseif ($via_mat_texto == "Piedra") {
      $fact_via = 1.10; 
   }elseif ($via_mat_texto == "Loseta") {
      $fact_via = 1.15; 
   }elseif ($via_mat_texto == "Cemento") {
      $fact_via = 1.20; 
   }elseif ($via_mat_texto == "Adoquin") {
      $fact_via = 1.15; 
   }elseif ($via_mat_texto == "Asfalto") {
      $fact_via = 1.20; 
   }


################################################################################
#------------------------- RUTA Y NOMBRE PARA GRABAR --------------------------#
################################################################################	

 $filename = "C:/apache/htdocs/tmp/boleta".$cod_cat.".html";
 
################################################################################
#------------------------ PREPARAR CONTENIDO PARA GRABAR ----------------------#
################################################################################	
$content = " 
<div align='right'>
<!-- Tabla 1 -->
<table style='font-family: Arial; font-size: 8pt; width: 700px;'>
   <tr>
      <td align='center' width='100%' colspan='2' bgcolor='#E9E9E9'>&nbsp</td>
   </tr>
   <tr>
       <td align='left'   width='50%' bgcolor='#E9E9E9'>SIG-CATASTRO</td>
      <td align='right'  width='50%' bgcolor='#E9E9E9'>Usuario: $user_id</td>         
   </tr>
   <tr>
      <td align='left'   width='50%' bgcolor='#E9E9E9'>GOBIERNO AUTONOMO MUNICIPAL DE PUERTO VILLARROEL</td>
      <td align='right'  width='50%' bgcolor='#E9E9E9'>Fecha/Hora:$fecha_actual </td>        
   </tr>   
</table>

<!-- Tabla 2 -->
<table style='font-family: Arial; font-size: 8pt; border-bottom: 1px solid #000; width: 700px;'>
   <tr   height='50' style='font-family: Arial; font-size: 16pt'>
      <td align='center' width='100%' colspan='3' bgcolor='#E9E9E9'>PROFORMA DETALLADA DE INMUEBLE</td>          
   </tr>
   <tr>
      <td align='left' width='20%' colspan='1' bgcolor='#E9E9E9'>P.M.C.: $pmcCeros </td>
      <td align='center' width='60%' colspan='1' bgcolor='#E9E9E9'>IMPUESTO MUNICIPAL A LA PROPIEDAD DE BIENES (IMPBI) - $gestion</td>       
      <td align='right' width='20%' colspan='1' bgcolor='#E9E9E9'>Fec.Emi:$fecha_emision</td>    
   </tr>    
</table>




<!-- Tabla 3 -->
<table style='font-family: Arial; font-size: 8pt; border-bottom: 1px solid #000; width: 700px;'>
   <tr>
      <td align='left' width='25%' colspan='1' bgcolor='#E9E9E9'>CODIGO MUNICIPAL:$cod_geo</td>
      <td align='center' width='50%' colspan='1' bgcolor='#E9E9E9'>CODIGO CATASTRAL:$cod_cat</td>       
      <td align='right' width='25%' colspan='1' bgcolor='#E9E9E9'>INMUEBLE No.$id_inmuCeros</td>    
   </tr>    
</table>

<!-- Tabla 4 -->
<table style='font-family: Arial; font-size: 8pt; border-bottom: 1px solid #000; width: 700px;'>
   <tr>
      <td align='left'   width='400px' colspan='2' bgcolor='#E9E9E9'><b>IDENTIFICACION CONSTRIBUYENTE</b></td>  
      <td align='left'   width='288px' colspan='2' bgcolor='#E9E9E9'><b>UBICACION DEL INMUEBLE</b></td>   
   </tr>
   <tr>
      <td align='left'   width='325px' bgcolor='#E9E9E9'>Sujeto Pasivo 1: $titular   </td> 
      <td align='left'   width='75px' bgcolor='#E9E9E9'>$tit_1ci_texto</td>    
      <td align='left'   width='288px' bgcolor='#E9E9E9'>Direccion:$direccion</td>      
   </tr>
   <tr>
      <td align='left'   width='325px' bgcolor='#E9E9E9'>Sujeto Pasivo 2: $titular2</td>
      <td align='left'   width='75px' bgcolor='#E9E9E9'>$tit_2ci_texto</td>  
      <td align='left'   width='288px' bgcolor='#E9E9E9'>Ciudad/Localidad: $distrito</td>     
   </tr>

</table>


<!-- Tabla 4 -->
<table style='font-family: Arial; font-size: 8pt; border-bottom: 1px solid #000; width: 700px;'>
   <tr>
      <td align='left'   width='550px' colspan='3' bgcolor='#E9E9E9'><b>AVALUO DEL TERRENO:&nbsp&nbsp$avaluo_terr Bs.-</b></td>  
      <td align='left' width='6%' >&nbsp</td>
      <td align='left'   width='1500px' colspan='2' bgcolor='#E9E9E9'><b>VALOR EMPRESA</b></td>   
   </tr>
   <tr>
      <td align='left' width='20%' bgcolor='#E9E9E9'>Superficie: </td>
      <td align='left' width='10%' bgcolor='#E9E9E9'>m2</td> 
      <td align='left' width='20%' bgcolor='#E9E9E9'>$sup_terr </td>
      <td align='left' width='6%' >&nbsp</td>
      <td align='center' width='15%' bgcolor='#E9E9E9'>Val en Libre al</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>Base Imponible</td>             
   </tr>
   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Zonas Homogeneas </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$ben_zona</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$val_m2_terr</td>
      <td align='left'   width='6%' >&nbsp</td>
      <td align='center' width='15%' bgcolor='#E9E9E9'>$fecha_emp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$base_imp_emp</td>             
   </tr>

   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Material de Via:</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$via_mat_texto</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_via</td>  
      <td align='left'   width='6%' >&nbsp</td> 
      <td align='center' width='15%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>&nbsp</td>      
   </tr>

   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Forma:</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$ter_form_texto</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_form</td>  
      <td align='left'   width='6%' >&nbsp</td> 
      <td align='center' width='30%' colspan='2' bgcolor='#E9E9E9'><b>EXENCION</b></td>     
   </tr>
   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Ubicacion:</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$ter_ubi_texto</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_ubi</td>  
      <td align='left'   width='6%' >&nbsp</td> 
      <td align='center' width='30%' colspan='2' bgcolor='#E9E9E9'>$texto_exencion</td>
   </tr>

   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Topografia: </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>$ter_topo_texto</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_incl</td> 
      <td align='left'   width='6%'  >&nbsp</td>  
      <td align='center' width='15%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>&nbsp</td>       

   </tr>

   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>Factor de Servicios: </td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>Minimo</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_min</td> 
      <td align='left'   width='6%'  >&nbsp</td>  
      <td align='center' width='30%' colspan='2'  bgcolor='#E9E9E9'><b>CONDONACION</b></td>

   </tr>
   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>Agua</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_agu</td> 
      <td align='left'   width='6%'  >&nbsp</td>  
      <td align='center' width='15%' bgcolor='#E9E9E9'>DESCUENTO DE:</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>$des_int</td> 
   </tr>
   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>Alcantarillado</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_alc</td> 
      <td align='left'   width='6%'  >&nbsp</td>  
      <td align='center' width='15%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>&nbsp</td> 
   </tr>
   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>Electricidad</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_luz</td> 
      <td align='left'   width='6%'  >&nbsp</td>  
      <td align='center' width='15%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>&nbsp</td> 
   </tr>
   <tr>
      <td align='left'   width='20%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='left'   width='10%' bgcolor='#E9E9E9'>Telefono</td> 
      <td align='left'   width='20%' bgcolor='#E9E9E9'>$fact_tel</td> 
      <td align='left'   width='6%'  >&nbsp</td>  
      <td align='center' width='15%' bgcolor='#E9E9E9'>&nbsp</td>
      <td align='center' width='14%' bgcolor='#E9E9E9'>&nbsp</td> 
   </tr>   
</table>


<!-- Tabla 5 -->
<table style='font-family: Arial; font-size: 8pt; border-bottom: 1px solid #000; width: 700px;' border='1'>
   <tr style='font-family: Tahoma; font-size: 9pt' height='15px'>
      <td align='left' valign='bottom' colspan='9'><b>&nbsp DATOS DE LAS CONSTRUCCIONES :</b></td>                         
   </tr>                                                 
   <tr height='10' style='font-family: Tahoma; font-size: 8pt; font-weight: bold'>
      <td align='center'>No.</td>
      <td align='center'>Piso</td>
      <td align='center'>Tipo</td>
      <td align='center'>$tit_col4</td>                    
      <td align='center'>Fac. Ant.</td>  
      <td align='center'>Valor x m2</td>  
      <td align='center'>Clase</td>                      
      <td align='center'>Superf. Mens.</td> 
      <td align='center'>$tit_col9</td>                                                   
   </tr>";

   $i = 0;
   if ($no_de_edificaciones > 8) {
      $fila_activa = true;
      $no_de_edif_real = $no_de_edificaciones;
      $no_de_edificaciones = 8;
   } else $fila_activa = false;
      $filas_vacias = 8 - $no_de_edificaciones;
      while ($i < $no_de_edificaciones) {
         $content = $content."                
         <tr height='20'>
            <td align='center'>$edi_num[$i]</td>
            <td align='center'>$edi_piso[$i]</td>
            <td align='center'>$edi_tipo[$i]</td>
            <td align='center'>$edi_ano[$i]</td>        
            <td align='center'>$factor_deprec[$i] </td>           
            <td align='center'>$calidad_const[$i]</td>  
            <td align='center'>$clase[$i]</td>                     
            <td align='center'>$area_edif[$i] $metro2</td> 
            <td align='center'>$avaluo[$i] </td>                                                     
         </tr>";  
         $i++;
      } 
   $i = 0;
   while ($i < $filas_vacias) {
      $content = $content."                
      <tr height='20'>
         <td align='center'>---</td>
         <td align='center'>---</td>
         <td align='center'>---</td>
         <td align='center'>---</td>                      
         <td align='center'>---</td>   
         <td align='center'>---</td>                      
         <td align='center'>---</td>   
         <td align='center'>---</td>  
         <td align='center'>---</td>                                                      
      </tr>";  
      $i++;
   }


$content = $content."
   <tr height='20'>
      <td align='left' colspan='6'>";
         if ($fila_activa) {
         $texto = "&nbsp --> El predio tiene en total $no_de_edif_real edificaciones.";
         } else {
         $texto = "&nbsp";
         }
         $content = $content."$texto
      </td>
      <td align='center'><b>TOTAL</b></td>
      <td align='center'><b>$edi_area</b></td>   
      <td align='center'><b>$savaluo</b></td>                                           
   </tr> 
</table>


<!-- Tabla 6 -->
<table style='font-family: Arial; font-size: 8pt; border-bottom: 1px solid #000; width: 700px;'>
   <tr>
      <td align='left' width='43%' colspan='4' bgcolor='#E9E9E9'><b>AVALUO DE LA CONSTRUCCION:&nbsp&nbsp $avaluo_const Bs.-</b></td>
      <td align='left' width='43%' colspan='2' bgcolor='#E9E9E9'><b>AVALUO TOTAL:&nbsp $avaluo_total Bs.-</b></td>    
   </tr>
   <tr>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Uso</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>Sup.Const.</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Avaluo Constr.</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>Tipo Ex.</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Monto Exento</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Base Imponible</td>    
   </tr>
   <tr>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$ter_uso_texto</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$sup_const</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$avaluo_const</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$tp_exen</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$monto_exen</td>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$avaluo_total</td>      
   </tr>   

</table>

<!-- Tabla 7 -->
<table style='font-family: Arial; font-size: 8pt; width: 700px;'>
   <tr>
      <td align='left'   colspan='7' width='344px' bgcolor='#E9E9E9'><b>CALCULO DE IMPUESTO</b></td> 
      <td align='left'   colspan='4' width='344px' bgcolor='#E9E9E9'><b>FECHA DE VENCIMIENTO: $fecha_venc </b></td> 
   </tr>
   <tr>
      <td align='center' width='10%' bgcolor='#E9E9E9'>Importe</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>Valor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Tip.Cam</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Mant.Valor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Interes</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Mora</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Incump.</td>
      <td align='center' width='6%'  bgcolor='#E9E9E9'>Adm.</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Form.</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>Descuento</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>Exencion</td>    
   </tr>
   <tr>
      <td align='center' width='10%' bgcolor='#E9E9E9'>$imp_neto</td> 
      <td align='center' width='10%' bgcolor='#E9E9E9'>$sal_favor</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$t_cam_actual</td> 
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$mant_val</td> 
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$interes</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$multa_mora</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$multa_incum</td>
      <td align='center' width='6%'  bgcolor='#E9E9E9'>$multa_admin</td>
      <td align='center' width='7%'  bgcolor='#E9E9E9'>$por_form</td>
      <td align='center' width='8%'  bgcolor='#E9E9E9'>$descuento</td> 
      <td align='center' width='7%'  bgcolor='#E9E9E9'>&nbsp</td>     
   </tr>
   <tr>
      <td align='left'   colspan='11' width='43%' >&nbsp</td> 
   </tr>
   <tr>
      <td align='left'   colspan='2' width='43%' bgcolor='#E9E9E9'><b>TOTAL A PAGAR: $total_a_pagar Bs.-</b></td> 
      <td align='left'   colspan='9' width='43%' bgcolor='#E9E9E9'><b>Son: $monto_en_letras 00/100 Bolivianos</b></td>
   </tr>
   <tr>
      <td align='left'   colspan='11' width='43%' >&nbsp</td> 
   </tr>
   <tr>
      <td align='left'   colspan='11' width='43%' >&nbsp</td> 
   </tr>
   <tr>
      <td align='center' colspan='11' width='43%' >&nbsp</td> 
   </tr>
   <tr>
      <td align='center' colspan='11' width='43%' >_______________________</td> 
   </tr>    
   <tr>
      <td align='center' colspan='11' width='43%' >F I R M A s</td> 
   </tr>     
</table>

<table border='0' width='800px'>
   <tr height='46'>
      <td align='center' width='14%'>&nbsp</td> 
      <td align='center' valign='bottom'>
      <a href='javascript:print(this.document)'>
      <img border='0' src='http://$server/$folder/graphics/printer.png' width='22' height='22'></a>
      </td>  
   </tr>
</table>  

</div>";



################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, 'w')) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>