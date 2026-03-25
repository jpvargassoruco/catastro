<?php
include "siicat_info_predio_leer_datos.php";
?>

<table  border="0" width="800px">
    <tr>
        <td align="center" colspan="2">Superficie</td>
        <td align="center" colspan="2">Info de la via</td>
        <td align="center" colspan="2">Topografía</td>
        <td align="center" colspan="2">Valor Zona</td>
    </tr>
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Superf. s/mens:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ter_smen";?> m²</td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Material de via:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$via_mat_texto";?></td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Topografia:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_topo_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Zona:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp <?php echo "$ben_zona";?></td>	            
    </tr>
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp s/documento:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ter_sdoc_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Tipo de Via:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$via_tip";?> </td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Forma:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_form_texto";?> </td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Valor:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp <?php echo "$ben_zona";?> </td>	            
    </tr>
    <tr>
        <td align="center" colspan="8">&nbsp</td>	 	          
    </tr>
</table>



<table  border="0" width="800px">
    <tr>
        <td align="center" colspan="2">Servicio Basicos</td>
        <td align="center" colspan="2">Ubicación</td>
        <td align="center" colspan="2">Medidas</td>
        <td align="center" colspan="2">Informacion adicional</td>
    </tr>
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Agua:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ser_agu_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Ubicacion:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_ubi_texto";?></td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Frente:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_fren_texto";?></td>	 </td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Destino uso:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp <?php echo "$ter_uso_texto";?></td></td>	            
    </tr>
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Luz:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ser_luz_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp No.Frente:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_nofr_texto";?></td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Contra Frente:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_con_fre";?> </td> 
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Edif Especial:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp <?php echo "$ter_eesp_texto";?> </td>	             
    </tr>
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Telefono:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ser_tel_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp </td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Fondo:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_fond_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Area de Servicio:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp NO</td>	            
    </tr> 
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Alcantarillado:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ser_alc_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp </td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Contra Fondo:</td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp <?php echo "$ter_con_fon";?></td>	 
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Inst.Sanitaria:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp <?php echo "$ter_san_texto";?></td>            
    </tr>
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp TV/Cable:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ser_cab_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp </td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp </td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Muro Perimetral:</td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp <?php echo "$ter_mur_texto";?></td>	            
    </tr>     
    <tr>
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp Gas Domiciliario:</td>	 	
        <td align="left" width="10%" class="bodyTextD_Small">&nbsp <?php echo "$ser_gas_texto";?></td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp </td>	  
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="12%" class="bodyTextD_Small">&nbsp </td>	
        <td align="left" width="12%" class="bodyTextH_Small">&nbsp </td>	 	
        <td align="left" width="16%" class="bodyTextD_Small">&nbsp </td>	            
    </tr> 
                  
    <tr>
        <td align="center" colspan="8">&nbsp</td>	 	          
    </tr>
</table>
<table border="0" width="800px">
    <tr height="20">
        <td width="1%">&nbsp </td>	 
        <td width="15%" align="left" valign="top"><b>&nbsp Observaciones:</b></td>	
        <td width="85%" align="left" class="bodyTextD_Small">&nbsp <?php echo "$ctr_obs_texto";?></td> 		 	  	 	 
    </tr>	 	     		  
</table>     


 
