<?php
 # FACTOR ZOOM EN EL PLANO CATASTRAL
 # SI NO SE PUEDE LEER TODOS LOS NOMBRES AUMENTAR EL VALOR POR 0.1
 #$factor_zoom_plano_catastral = 2.6;  #(valor original)
 $factor_zoom_plano_catastral = 2.5;  #(valor ajustado) 
 $factor_zoom_linea_y_nivel = 2.6;

 # INFORMACION DEL MUNICIPIO
 # $uv_dist =  U.V. o Distrito (catastral)
 $uv_dist = "Dist.";   
 $dom_ciu_default = "ValleGrande";
 $dom_ciu_MAYUS = "VALLEGRANDE"; 
 $cod_mun = "07-08-01-01";
 $cod_geo = "07-08-01-01"; 
 $depart = "SANTA CRUZ";
 $depart_2digit = "SC"; 
 $depart_3digit = "SCZ"; 
 $provincia = "VALLEGRANDE";
 $seccion = "PRIMERA";
 $municipio = "VALLEGRANDE";
 $municipio_min = "Vallegrande"; 
 $distrito = "VALLEGRANDE";
 $distrito_min = "Vallegrande"; 
 $comunidad = "VALLEGRANDE";
 $nomlog = "vallegrande.png"; 
 # NOMBRE DE LA BASE DE DATOS
 $folder  = "vallegrande";

 # CENTRO DEL PUEBLO PARA LA ZONIFICACION (CENTRO DE LA PLAZA)  
 $centro_del_pueblo_para_zonas_x = 383011;
 $centro_del_pueblo_para_zonas_y = 7955296;
 
 
 # EXTENSIONES MINIMA Y MAXIMA PERMITIDA EN UTM  
 $minimo_permitido_x = 378624;
 $maximo_permitido_x = 387975; 
 $minimo_permitido_y = 7953071;
 $maximo_permitido_y = 7958105;

 # EXTENSION PARA EL MAPFILE 

 $mapfile_extent = "382109 384375 7954052 7956657"; 
 $mapfile_extent_reference = "382109 384375 7954052 7956657";
 
 # CANTIDAD DE COLUMNAS DE LA TABLA EXCEL A IMPORTAR
 $excel_cols = 95;	
 $excel_cols_edif = 28;	 
 $excel_cols_acteco = 19;
 $excel_cols_vehic = 27; 
 
 # TAMAÑO MINIMO DE UN PREDIO (EN M2)
 $min_pred_sup = 100;   
 
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
 
?>