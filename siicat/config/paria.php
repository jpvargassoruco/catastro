<?php

 # IP DEL SERVIDOR
 #$server = "200.58.87.180";
 $server = "127.0.0.1";
 # CONEXION CON LA BASE DE DATOS
 $db_passw = "qwert";$db_name = "paria"; $db_user = "postgres";   
 
 $dbconn = pg_connect("host=$server dbname=$db_name user=$db_user password=$db_passw")
 or die('<br>NO se pude conectar a la base de datos! <br>Verifique que PostgreSQL esté funcionando 
      como servicio de Windows y que la IP de la computadora esté registrada en el archivo ../apache/data/pg_hba.conf ' . pg_last_error());  
 
 # IP DEL VISITANTE
 $ip = $_SERVER['REMOTE_ADDR']; 
 
   # AJUSTAR FECHA Y HORA
   date_default_timezone_set('America/La_Paz');
   $date = getdate();

   
   $dia_actual = $date['mday'];
   $mes_actual = $date['mon'];  
   $ano_actual = $date['year'];
   $ano_actua2 = substr($ano_actual,-2); 
   $hours      = $date['hours'];
   $minutes    = $date['minutes'];
   $seconds    = $date['seconds'];
 # Ajustar números con 1 cifra
 if ($dia_actual < 10) {
    $dia_actual = "0".$dia_actual;
 }
 if ($mes_actual < 10) {
    $mes_actual = "0".$mes_actual;
 } 
 $fecha = $ano_actual."-".$mes_actual."-".$dia_actual;
 $fecha2 = $dia_actual."/".$mes_actual."/".$ano_actual; 
 
 if ($hours < 10) {
    $hours = "0".$hours;
 }
 $minutes = $date['minutes'];
 if ($minutes < 10) {
    $minutes = "0".$minutes;
 } 
 $seconds = $date['seconds'];
 if ($seconds < 10) {
    $seconds = "0".$seconds;
 } 
 $hora = $hours.":".$minutes.":".$seconds;
 /*
 $pageview_new = $date['0'];
 $expiration_time = 1000; 
    */
 # AÑO Y MES EN QUE SE CAMBIA DEL SIIM AL SISTEMA DE CATASTRO 
 $ano_cambio_de_sistema = 2023;
 $mes_cambio_de_sistema = 6;
 
 # ULTIMO ANO QUE APARECE PARA COBRAR
 if ($mes_actual > 12) {
   $ult_ano = $ano_actual-3; 
 } else $ult_ano = $ano_actual-3;  
 

 


################################################################################
#------------------------       CONTROL DE No C.C.       ----------------------#
################################################################################
$sql="SELECT * FROM config WHERE id = 1";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);
####################################
# FACTOR ZOOM EN EL PLANO CATASTRAL
####################################
$factor_zoom_plano_catastral = $info['factor_zoom_plano_catastral'];
$factor_zoom_linea_y_nivel = $info['factor_zoom_linea_y_nivel'];

####################################
# INFORMACION DEL MUNICIPIO 07
####################################
$uv_dist = $info['uv_dist'];
$dom_ciu_default = $info['dom_ciu_default'];


$cod_mun = $info['cod_geo'];  
$cod_geo = $info['cod_geo']; 

$cod_dep = substr($cod_geo, 0,2);
$cod_pro = substr($cod_geo, 3,2);
$cod_mun = substr($cod_geo, 6,2);
$cod_dis = substr($cod_geo, 9,2);


$folder = $info['folder']; 
$depart = $info['depart']; 

$depart_2digit = $info['depart_2digit']; 
$depart_3digit = $info['depart_3digit'];
$provincia = $info['provincia'];
$seccion = $info['seccion']; 
$municipio = $info['municipio'];
$municipio_min = ucwords($municipio);
$distrito = $info['distrito'];
$distrito_min = ucwords($distrito);
$comunidad = $info['comunidad'];
$nomlog = trim($info['nomlog']); 
$dir_mun = trim($info['dir_mun']);  
$dir_cor = trim($info['dir_cor']); 
$dir_fon = $info['dir_fon'];
$municipio_abr =  $info['municipio_abr'];
$ciudad = $info['ciudad']; 
$NomSisCoo = $info['nomsiscoo']; 


# CENTRO DEL PUEBLO PARA LA ZONIFICACION (CENTRO DE LA PLAZA)  
$centro_del_pueblo_para_zonas_x = $info['centro_del_pueblo_para_zonas_x'];
$centro_del_pueblo_para_zonas_y = $info['centro_del_pueblo_para_zonas_y'];

# EXTENSIONES MINIMA Y MAXIMA PERMITIDA EN UTM  
$minimo_permitido_x = $info['minimo_permitido_x'];
$maximo_permitido_x = $info['maximo_permitido_x'];
$minimo_permitido_y = $info['minimo_permitido_y'];
$maximo_permitido_y = $info['maximo_permitido_y'];


# DATOS DEL CERTIFICADO CON PLANO

$ley_creacion = utf8_decode($info['ley_creacion']);

# CENTRO DEL MAPA
$centro_del_mapa_x = ($maximo_permitido_x - $minimo_permitido_x)/2 + $minimo_permitido_x;  
$centro_del_mapa_y = ($maximo_permitido_y - $minimo_permitido_y)/2 + $minimo_permitido_y; 

# EXTENSION PARA EL MAPFILE 
$mapfile_extent = $info['mapfile_extent'];
$mapfile_extent_reference = $info['mapfile_extent_reference'];

 # NOMENCLATURA
 $Predio = "Predio";
 $predio = "predio";

 # COPYRIGHT 
 $copyright ="igm"; 
 
 # CENTRO DEL MAPA
 $centro_del_mapa_x = ($maximo_permitido_x - $minimo_permitido_x)/2 + $minimo_permitido_x;  
 $centro_del_mapa_y = ($maximo_permitido_y - $minimo_permitido_y)/2 + $minimo_permitido_y;  
 

$nom_sis_coo = "Coordenadas UTM - Zona 19 - DATUM WGS-84";

 # ESCAPES (REEMPLAZOS)
 $esc1 = pg_escape_string('SRID=-1;POINT(');
 $esc2 = pg_escape_string('SRID=-1;MULTILINESTRING((');
 $esc3 = pg_escape_string('))'); 
 $esc4 = pg_escape_string('SRID=-1;MULTIPOLYGON(((');
 $esc5 = pg_escape_string(')))'); 

 
 # CANTIDAD DE COLUMNAS DE LA TABLA EXCEL A IMPORTAR
 $excel_cols = 95;	
 $excel_cols_edif = 28;	 
 $excel_cols_acteco = 19;
 $excel_cols_vehic = 27; 
 
 # TAMAÑO MINIMO DE UN PREDIO (EN M2)
 $min_pred_sup = 100;   
 
# USA SUPERFICIE SEGUN MESURA O SUPERFICE SEGUN DOCUMENTO
$usa_seg_men = true;


 # MANEJO DE ERRORES
 ini_set("display_errors", 0); 
 ini_set("log_errors", 0);
 ini_set("error_log", "C:/apache/siicat/log/errorlog.txt");
 ini_set("error_prepend_string", "Ha ocurrido un error en el programa. Pongase en contacto con el administrador del sistema!"); 
 ini_set("error_append_string", "");
 
 # PLANO TEXTOS CERTIFICACO CATASTRAL
 $titulo1 = "TERCERA SECCION PARIA CON SU CAPITAL ".$distrito;
 $titulo2 = "Ley de creacion No. 2329 - 4/02/2002";
 $titulo3 = "CERCADO - ORURO - BOLIVIA";

 # MAXIMOS DE LONGITUD DE CODIGO (PERMITIDOS SON 2-4 DIGITOS)
 $max_strlen_uv = 2;    # U.V./Distrito
 $max_strlen_man = 4;    # Manzano
 $max_strlen_pred = 3;    # Predio
 $max_strlen_blq  = 2;    # Bloque/Sector
 $max_strlen_piso = 3;    # Piso
 $max_strlen_apto = 4;    # Apartamento
 
 # MAXIMOS DE LONGITUD DE STRING 
 $max_strlen_dir_nom = 28;   # Nombre de Calle, Avenida o Plaza
 $max_strlen_dir_num = 5;    # Número
 $max_strlen_dir_edif = 15;    # Edificio
 $max_strlen_dir_cond = 15;    # Condominio
 $max_strlen_dir_bloq = 3;    # Bloque
 $max_strlen_dir_piso = 2;    # Piso
 $max_strlen_dir_apto = 4;    # Apartamento
 $max_strlen_nombre = 15;    # Nombres del Contribuyente
 $max_strlen_apellido = 25;    # Apellidos del Contribuyente
 $max_strlen_nit = 12;    # NIT
 $max_strlen_ci = 12;    # No. de Carnet 
 $max_strlen_ciu = 25;    # Ciudad de Domicilio
 $max_strlen_bar = 25;        # Barrio 
 $max_strlen_dir = 44;        # Dirección
 $max_strlen_adq_doc = 120;   # Documento de Adquisición
 $max_strlen_der_num = 50;    # Numero de DDRR 
 $max_strlen_obs = 200;       # Observaciones
 $max_strlen_col = 115;       # Colindantes
 $max_strlen_col_med = 75;    # Colindantes (medidas)
 $max_strlen_pol = 3;
 $max_strlen_par = 3;
 $max_strlen_pmc = 15; 


 $iconos = "http://$server/$folder/iconos";

?>