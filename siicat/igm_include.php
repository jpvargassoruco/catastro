<?php

switch($_GET['mod']) {
	case "1":
	include "c:/apache/siicat/busqueda.php";
	break;		
	case "2": 
	include "c:/apache/siicat/siicat_ver_mapa.php";		                
	break; 					                     
	case "3":		
	include "c:/apache/siicat/siicat_anadir_datos.php";
	break;
	case "4": 
	include "c:/apache/siicat/check_busqueda.php";		                
	break;    				
	case "5":		
	include "c:/apache/siicat/siicat_busqueda_resultado.php";
	break;	
	case "6":		
	include "c:/apache/siicat/siicat_modificar_datos.php";
	break;	
	case "7":		
	include "c:/apache/siicat/siicat_check_datos.php";
	break;			
	case "8":		
	include "c:/apache/siicat/siicat_modificar_codigo.php";
	break;								
	case "10":		
	include "c:/apache/siicat/siicat_ver_geometria.php";
	break;	
	case "11":		
	include "c:/apache/siicat/siicat_anadir_geometria.php";
	break;
	case "12":		
	include "c:/apache/siicat/igm_anadir_geometria_mz.php";
	break;				
	case "14":
	include "c:/apache/siicat/igm_anadir_geometria_pr.php";
	break;
	case "16":
	include "c:/apache/siicat/igm_anadir_geometria_ed.php";
	break;			
	case "17":
	include "c:/apache/siicat/igm_anadir_geometria_mv.php";
	break;					
	case "13":
	include "c:/apache/siicat/siicat_modificar_borrar_objetos.php";
	break;						
	case "15":
	include "c:/apache/siicat/siicat_gravamen.php";
	break;												
	case "20":		
	include "c:/apache/siicat/siicat_ver_edif.php";
	break;
	case "21":		
	include "c:/apache/siicat/siicat_anadir_edif.php";
	break;
	case "22":		
	include "c:/apache/siicat/siicat_modificar_edif.php";
	break;	
	case "25":		
	include "c:/apache/siicat/siicat_herramientas.php";
	break;	
	case "26":		
	include "c:/apache/siicat/siicat_datos_socioeco.php";
	break;
	case "29":		
		include "c:/apache/siicat/siicat_check_edif.php";
		break;																				
	case "30":		
		include "c:/apache/siicat/siicat_plano_catastral.php";
		break;
	case "301":		
		include "c:/apache/siicat/plano_catastral_lote.php";
		break;
	case "302":		
		include "c:/apache/siicat/plano_ubicacion.php";
		break;			
	case "31":		
		include "c:/apache/siicat/certificado_catastral.php";
		break;				
	case "320":		
		include "c:/apache/siicat/informe_empadronamiento.php";
		break;
	case "321":		
		include "c:/apache/siicat/informe_empadronamiento2.php";
		break;
	case "322":		
		include "c:/apache/siicat/informe_empadronamiento3.php";
		break;	
	case "323":		
		include "c:/apache/siicat/igm_informe_tecnico.php";
		break;
	case "324":		
		include "c:/apache/siicat/informe_tecnico_plano.php";
		break;
	case "325":		
		include "c:/apache/siicat/igm_informe_tecnico3.php";
		break;						
	case "33":		
		include "c:/apache/siicat/igm_uso_de_suelo.php";
		break;								
	case "34":		
		include "c:/apache/siicat/igm_linea_nivel.php";
		break;
	case "341":		
		include "c:/apache/siicat/igm_linea_nivel2.php";
		break;		
	case "35":		
		include "c:/apache/siicat/igm_plano_catastral2.php";
		break;
	case "36":		
		include "c:/apache/siicat/igm_aprobacion_plano.php";
		break;	
	case "37":		
		include "c:/apache/siicat/plano_certificado_Catastral1.php";
		break;
	case "371":		
		include "c:/apache/siicat/plano2_certificado_Catastral.php";
		break;
	case "38":		
		include "c:/apache/siicat/plano_certificado_Catastral2.php";
		break;
	case "39":
		include "c:/apache/siicat/siicat_mapas_tematicos.php";
		break;																			
	case "41":		
		include "c:/apache/siicat/siicat_busqueda.php";
		break;						
	case "42":		
		include "c:/apache/siicat/siicat_ver_mapa_rural.php";
		break;		
	case "43":		
		include "c:/apache/siicat/siicat_mapas_tematicos_rural.php";
		break;	
	case "44":		
		include "c:/apache/siicat/siicat_rural_resultado.php";
		break;
	case "59":		
		include "c:/apache/siicat/siicat_impuestos_documentos.php";
		break;											
	case "60":		
		include "c:/apache/siicat/siicat_impuestos.php";
		break;
	case "61":		
		include "c:/apache/siicat/siicat_impuestos_boletas.php";
		break;				
	case "62":		
	include "c:/apache/siicat/siicat_impuestos_boleta_de_pago.php";				 
	break;
	case "621":		
	include "c:/apache/siicat/igm_impuestos_boleta_de_pago.php";				 
	break;

	case "63":		
	include "c:/apache/siicat/impuestos_sello.php";				 
	break;
	case "64":		
	include "c:/apache/siicat/siicat_impuestos_cotizaciones.php";
	break;		
	case "65":		
	include "c:/apache/siicat/siicat_impuestos_tablas.php";
	break;
	case "66":		
	include "c:/apache/siicat/siicat_impuestos_ajustes.php";
	break;					
	case "67":
	include "c:/apache/siicat/igm_transferencia.php";
	break;					
	case "68":		
	include "c:/apache/siicat/igm_check_transferencia.php";
	break;	
	case "69":		
	include "c:/apache/siicat/igm_impuestos_transferencia.php";
	break;															
	case "70":		
	include "c:/apache/siicat/impuestos_reportes.php";
	break;
	case "71":		
	include "c:/apache/siicat/siicat_impuestos_reporte_tablas_base.php";
	break;		
	case "72":		
		include "c:/apache/siicat/impuestos_reporte_ingresos.php";
		break;
	case "73":		
		include "c:/apache/siicat/siicat_impuestos_reporte_boletas_impresas.php";
		break;
	case "75":		
		include "c:/apache/siicat/form_caja.php";
		break;
	case "76":		
		include "c:/apache/siicat/siicat_tasas_impresos.php";
		break;	
	case "77":		
		include "c:/apache/siicat/tasas_subniveles.php";
		break;		
	case "78":		
		include "c:/apache/siicat/recursos.php";
		break;											 								
	case "80":		
		include "c:/apache/siicat/siicat_pdf_generar.php";
		break;	
	case "81":		
		include "c:/apache/siicat/siicat_pdf.php";
		break;																												
	case "90":		
		include "c:/apache/siicat/siicat_sistema.php";
		break;					
	case "91":		
		include "c:/apache/siicat/siicat_backup_iframe.php";
		break;	
	case "95":		
		include "c:/apache/siicat/siicat_contactos.php";
		break;		
	case "97":		
		include "c:/apache/siicat/siicat_cambiar_password.php";
		break;										
	case "98":		
		include "c:/apache/siicat/registro_ver.php";
		break;								
	case "99":		
		include "c:/apache/siicat/siicat_usuarios.php";
		break;	
	case "101":		
	include "c:/apache/siicat/siicat_busqueda.php";
	break;						
	case "102":		
		include "c:/apache/siicat/siicat_check_patente.php";
		break;		
	case "103":		
		include "c:/apache/siicat/siicat_patentes_resultado.php";
		break;	
	case "104":		
	include "c:/apache/siicat/siicat_patentes_tablas.php";
	break;			
	case "105":		
	include "c:/apache/siicat/siicat_patentes_licencia_imprimir.php";
	break;	
	case "106":		
	include "c:/apache/siicat/siicat_patentes_rubros.php";
	break;															
	case "111":		
	include "c:/apache/siicat/siicat_busqueda.php";
	break;						
	case "112":		
	include "c:/apache/siicat/siicat_check_vehic.php";
	break;		
	case "113":		
	include "c:/apache/siicat/siicat_vehic_resultado.php";
	break;	
	case "114":		
	include "c:/apache/siicat/siicat_vehic_listado.php";
	break;	
	case "115":		
	include "c:/apache/siicat/siicat_vehic_estadisticas.php";
	break;	
	case "121":		
	include "c:/apache/siicat/contrib_busqueda.php";
	break;	
	case "122":		
	include "c:/apache/siicat/contrib_check_new.php";
	break;		
	case "123":		
	include "c:/apache/siicat/siicat_contrib_resultado.php";
	break;
	case "124":		
	include "c:/apache/siicat/contrib_check_mod.php";
	break;											  
	default:                  
	include "c:/apache/siicat/siicat_busqueda.php";     
	break;
}
?>