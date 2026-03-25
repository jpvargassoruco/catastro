<?php
################################################################################
#--------------------------- CHEQUEAR LOS UVS ---------------------------------#
################################################################################	
$sql="SELECT DISTINCT cod_uv FROM manzanos WHERE cod_uv > '8' ORDER BY cod_uv";
$numero_de_uvs_nuevos= pg_num_rows(pg_query($sql));
$i = 0; $r = 255; $g = 159; $b = 174;
$result_uv = pg_query($sql);
while ($line = pg_fetch_array($result_uv, null, PGSQL_ASSOC)) {			 	 			           
   foreach ($line as $col_value) {
      $cod_uv_nuevo[$i] = $col_value;
			$sql="SELECT nombre FROM uvs WHERE cod_uv = '$col_value'";
      $check_nombre = pg_num_rows(pg_query($sql));
			if ($check_nombre > 0) {
         $result_nombre=pg_query($sql);
         $info_nombre = pg_fetch_array($result_nombre, null, PGSQL_ASSOC);
         $cod_uv_nuevo_nombre[$i] = $info_nombre['nombre']; 
         pg_free_result($result_nombre);			
			} else $cod_uv_nuevo_nombre[$i] = "-";
			$cod_uv_nuevo_color[$i] = "$r $g $b";
			$r = $r -50;
			if ($r < 0) {
			   $r = $r + 255;
			} 
			$g = $g - 50;
			if ($g < 0) {
			   $g= $g + 255;
			} 			
			$b = $b + 20;
			if ($b > 255) {
			   $b = $b - 255;
			} 			
			$i++;
	 }
}
pg_free_result($result_uv);
################################################################################
#---------------------- CREAR COLORES PARA USO DE SUELO -----------------------#
################################################################################	
$sql="SELECT DISTINCT uso FROM uso_de_suelo ORDER BY uso";
$numero_de_usos = pg_num_rows(pg_query($sql));
$i = 0; $r = $g = $b = 230;
$delta = ROUND (150/$numero_de_usos,0);
$result_uso = pg_query($sql);
while ($line = pg_fetch_array($result_uso, null, PGSQL_ASSOC)) {			 	 			           
   foreach ($line as $col_value) {
      $uso[$i] = $col_value;
			$uso_color[$i] = "$r $g $b";
			$r = $g = $b = $r - $delta;	
			$i++;
	 }
}
pg_free_result($result_uso);
################################################################################
#-------------------- CREAR COLORES PARA MATERIAL DE VIA ----------------------#
################################################################################	
$sql="SELECT DISTINCT material FROM material_de_via ORDER BY material";
$numero_de_materiales = pg_num_rows(pg_query($sql));
$i = 0; $r = $g = $b = 230;
$delta = ROUND (150/$numero_de_usos,0);
$result_mat = pg_query($sql);
while ($line = pg_fetch_array($result_mat, null, PGSQL_ASSOC)) {			 	 			           
   foreach ($line as $col_value) {
      $mat[$i] = $col_value;
			$mat_color[$i] = "$r $g $b";
			$r = $g = $b = $r - $delta;	
			$i++;
	 }
}
pg_free_result($result_mat); 
################################################################################
#----------------------- RUTA Y NOMBRE PARA GRABAR ----------------------------#
################################################################################	

$filename = "C:/apache/catastro/mapa/igm.map";
 
################################################################################
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "MAP
#
# Start of map file - created $fecha - $hora
#
NAME 'IGM'
STATUS ON

PROJECTION
     	'init=epsg:32720'	
END

SIZE   700 400
EXTENT $mapfile_extent
UNITS  meters

SYMBOLSET 'c:/apache/catastro/mapa/symbols/symbset.sym'
FONTSET   'c:/apache/catastro/mapa/fonts/fonts.fnt'
IMAGECOLOR 255 255 255

#
# Start of web interface definition
#
WEB
 LOG igm.log
 TEMPLATE igm.html
 IMAGEPATH 'c:/apache/htdocs/tmp/'
 IMAGEURL 'http://$server/tmp/'
 EMPTY 'http://$server/$folder/nada.html'
 MINSCALEDENOM 400
 MAXSCALEDENOM 100000

 METADATA
  WMS_ONLINERESOURCE 'http://$server/cgi-bin/mapserv.exe?map=c:/apache/catastro/mapa/igm.map'
  WMS_SRS 'epsg:32720'
  WMS_ACCESSCONSTRAINTS 'none'
  WMS_TITLE 'IGM'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  WMS_ABSTRACT 'Sistema Integral de Catastro'
 END  #METADATA

END  #HEADER

OUTPUTFORMAT
  NAME 'jpg'
	DRIVER 'GD/JPEG'
	MIMETYPE 'image/jpeg'
	IMAGEMODE RGB
	EXTENSION 'jpg'
	FORMATOPTION 'QUALITY=100'
END

#----------------------------------------------------------- MAPA EN TEMPLATE DE CONSULTA
QUERYMAP
  SIZE 150 150
  STATUS ON #OFF
  STYLE HILITE
  COLOR 255 0 0
END
#----------------------------------------------------------- MAPA EN TEMPLATE DE CONSULTA
#----------------------------------------------------------- MAPA DE UBICACION
REFERENCE
  STATUS ON
  IMAGE datos/reference.png
  SIZE 120 120
  EXTENT $mapfile_extent_reference
  COLOR -1 -1 -1
  OUTLINECOLOR 255 0 0
END  #REFERENCE
#----------------------------------------------------------- MAPA DE UBICACION
#----------------------------------------------------------- LEYENDA
LEGEND
  STATUS ON
  KEYSIZE 16 8
  TEMPLATE 'leyenda.html'
  LABEL
    COLOR 120 120 120
  END # ENDE LABEL
END   #LEGEND
#----------------------------------------------------------- LEYENDA
#----------------------------------------------------------- BARRA DE ESCALA
SCALEBAR
 STATUS EMBED
 POSITION lr
 STYLE 0
 INTERVALS 4
 IMAGECOLOR 255 255 255
 LABEL
  COLOR 0 0 0
  SIZE SMALL
 END  #ENDE LABEL
 SIZE 200 3
 TRANSPARENT ON
 COLOR 0 0 0
 BACKGROUNDCOLOR 255 255 255
 OUTLINECOLOR 100 100 100
 UNITS METERS
END   #SCALEBAR
#----------------------------------------------------------- BARRA DE ESCALA
#
# Start of layer definitions #
#
#----------------------------------------------------------- U.V.s
LAYER
  CONNECTIONTYPE postgis
  NAME 'UVs'
  GROUP 'Manzanos'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from manzanos USING UNIQUE oid'
  STATUS ON
  TYPE Polygon
	TEMPLATE 'igm_query_manzanos.htm'
  METADATA
    WMS_SRS 'epsg:32720'
    WMS_TITLE 'UVs'
    WMS_GROUP_TITLE 'Manzanos'
    WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  END

PROJECTION
     	'init=epsg:32720'
END

    CLASSITEM 'cod_uv'

      CLASS
       NAME 'Distrito 1'
			 EXPRESSION '1'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 192 252 255
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
      END  # CLASS
      
			CLASS
       NAME 'Distrito 2'
			 EXPRESSION '2'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 155 255 155
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
      END  # CLASS
			
			CLASS
       NAME 'Distrito 3'
			 EXPRESSION '3'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 193 193 193
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	 
			 
			CLASS
       NAME 'Distrito 4'
			 EXPRESSION '4'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 230 230 203
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END		 
      END  # CLASS		
			
		  CLASS
       NAME 'Distrito 5'
			 EXPRESSION '5'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 249 165
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			
			  CLASS
       NAME 'Distrito 6'
			 EXPRESSION '6'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 199 174
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			
		  CLASS
       NAME 'Distrito 7'
			 EXPRESSION '7'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 183 223 134
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			
		  CLASS
       NAME 'Distrito 8'
			 EXPRESSION '8'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 156 157 220
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS";
$i = 0;
while ($i < $numero_de_uvs_nuevos) {
$content = $content."											
		  CLASS
       NAME 'UV $cod_uv_nuevo[$i]: $cod_uv_nuevo_nombre[$i]'
			 EXPRESSION '$cod_uv_nuevo[$i]'
			 STYLE
			    #SYMBOL 'circle'
          COLOR $cod_uv_nuevo_color[$i]
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS";
   $i++;
}
$content = $content."								
		  CLASS
			 EXPRESSION /./
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 255 215
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			 
END  # END OF LAYERFILE		
#----------------------------------------------------------- Distritos
#----------------------------------------------------------- Manzano-Anotation
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'manzanos_anot'
	GROUP 'Manzanos'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from manzanos USING UNIQUE oid'
  STATUS ON
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'manzanos_anot'
 WMS_GROUP_TITLE 'Manzanos'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

 MINSCALE 100
 MAXSCALE 10000

PROJECTION
     	'init=epsg:32720'			
END

  CLASSITEM 'cod_man'
  LABELITEM 'cod_man'		 
		CLASS
     NAME 'Predios'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 13
       ANGLE 0
       COLOR 100 100 100
       BUFFER 2
			 OFFSET 1 1
       #BACKGROUNDCOLOR 0 0 0
			 # OUTLINECOLOR 0 0 0
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
     END  # CLASS
		 
END  # END OF LAYERFILE
#----------------------------------------------------------- Manzano-Anotation
#----------------------------------------------------------- Uso de Suelo
LAYER
  CONNECTIONTYPE postgis
  NAME 'Uso de Suelo'
  GROUP 'Uso de Suelo'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from uso_de_suelo USING UNIQUE oid'
  STATUS ON
  TYPE Polygon
#  TEMPLATE 'catbr_query_manzanos.htm'	
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Uso de Suelo'
 WMS_GROUP_TITLE 'Uso de Suelo'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

    CLASSITEM 'uso'";
$i = 0;
while ($i < $numero_de_usos) {
$content = $content."
											
		  CLASS
       NAME '$uso[$i]'
			 EXPRESSION '$uso[$i]'
			 STYLE
          COLOR $uso_color[$i]
          OUTLINECOLOR 0 0 0 #40 40 40
			    SIZE 2
			 END
			END  # CLASS";
   $i++;
}
$content = $content."		 									
		  CLASS
			 EXPRESSION /./
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 255 255
          OUTLINECOLOR 0 0 0 #40 40 40
			    SIZE 2
			 END
			END  # CLASS		
			 
END  # END OF LAYERFILE		
#----------------------------------------------------------- Uso de Suelo
#----------------------------------------------------------- Uso de Suelo-Anotation
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'Uso de Suelo'
  GROUP 'Uso de Suelo'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from uso_de_suelo USING UNIQUE oid'
  STATUS ON
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'poly_anot'
 WMS_GROUP_TITLE 'Poligono'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

 MINSCALEDENOM 100
 MAXSCALEDENOM 100000

PROJECTION
     	'init=epsg:32720'			
END

  CLASSITEM 'uso'
  LABELITEM 'uso'		 
		CLASS
     NAME 'Zonas'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 9
       ANGLE 0
       COLOR 100 100 100
       BUFFER 0
			 OFFSET 2 2
       #BACKGROUNDCOLOR 0 0 0
			 # OUTLINECOLOR 0 0 0
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
     END  # CLASS
		 
END  # END OF LAYERFILE
#----------------------------------------------------------- Uso de Suelo-Anotation
#----------------------------------------------------------- Material de Via
LAYER
  CONNECTIONTYPE postgis
  NAME 'Material de Via'
  GROUP 'Material de Via'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from material_de_via USING UNIQUE oid'
  STATUS ON
  TYPE Polygon
#  TEMPLATE 'catbr_query_manzanos.htm'	
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Material de Via'
 WMS_GROUP_TITLE 'Material de Via'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

    CLASSITEM 'material'";
$i = 0;
while ($i < $numero_de_materiales) {
$content = $content."
											
		  CLASS
       NAME '$mat[$i]'
			 EXPRESSION '$mat[$i]'
			 STYLE
          COLOR $mat_color[$i]
          OUTLINECOLOR 0 0 0 #40 40 40
			    SIZE 2
			 END
			END  # CLASS";
   $i++;
}
$content = $content."		 									
		  CLASS
			 EXPRESSION /./
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 255 255
          OUTLINECOLOR 0 0 0 #40 40 40
			    SIZE 2
			 END
			END  # CLASS		
			 
END  # END OF LAYERFILE		
#----------------------------------------------------------- Material de Via
#----------------------------------------------------------- Material de Via-Anotation
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'Material de Via'
  GROUP 'Material de Via'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from material_de_via USING UNIQUE oid'
  STATUS ON
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'poly_anot'
 WMS_GROUP_TITLE 'Poligono'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

 MINSCALEDENOM 100
 MAXSCALEDENOM 100000

PROJECTION
     	'init=epsg:32720'			
END

  CLASSITEM 'material'
  LABELITEM 'material'		 
		CLASS
     NAME 'Material'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 9
       ANGLE 0
       COLOR 50 50 50
       BUFFER 0
			 OFFSET 2 2
       #BACKGROUNDCOLOR 0 0 0
			 # OUTLINECOLOR 0 0 0
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
     END  # CLASS
		 
END  # END OF LAYERFILE
#----------------------------------------------------------- Material de Via-Anotation
#----------------------------------------------------------- Predios
LAYER
  CONNECTIONTYPE postgis
  NAME 'predios'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from predios_ocha USING UNIQUE oid'	
  STATUS OFF
  TYPE Polygon
 # TEMPLATE 'igm_query_predios.htm'
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Predios'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

  CLASSITEM 'activo'

      CLASS
       NAME 'Delimitación'
			 EXPRESSION '1'
			 STYLE
			    SYMBOL 'circle'
          COLOR -1 -1 -1
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 1
			 END
      END  # CLASS

END  # END OF LAYERFILE
#----------------------------------------------------------- Predios
#----------------------------------------------------------- Predios-Anotación
LAYER
  DEBUG ON
  CONNECTIONTYPE postgis
  NAME 'predios_anot'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from predios_ocha USING UNIQUE oid'	
  STATUS OFF
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Predios'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

  MINSCALE 100
	MAXSCALE 2500
	CLASSITEM 'cod_pred'
	LABELITEM 'cod_pred'

      CLASS
			NAME 'Código Catastral'
      EXPRESSION /./
         LABEL
           TYPE TRUETYPE
           FONT bluehigh
           SIZE 11
           ANGLE 0
           COLOR 0 0 153 #40 40 40
           BUFFER -1
           POSITION cc
           PARTIALS FALSE
			     FORCE TRUE
         END
      END
END  # END OF LAYERFILE
#----------------------------------------------------------- Predios-Anotación
#----------------------------------------------------------- Edificaciones
LAYER
  CONNECTIONTYPE postgis
  NAME 'edificaciones'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from edificaciones USING UNIQUE oid'	
  STATUS ON
  TYPE Polygon
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Edificaciones'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

MINSCALE 100
MAXSCALE 1500

  CLASSITEM 'cod_uv'

      CLASS
       NAME 'Edificaciones'	 
		  STYLE
			    SYMBOL 'circle'
          COLOR -1 -1 -1
          OUTLINECOLOR 0 153 0 #40 40 40
			    SIZE 1
			 END
      END  # CLASS

END  # END OF LAYERFILE
#----------------------------------------------------------- Edificaciones
#----------------------------------------------------------- Edificaciones-Anotación
LAYER
  CONNECTIONTYPE postgis
  NAME 'edificiones'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from edificaciones USING UNIQUE oid'	
  STATUS ON
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Edificaciones'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

MINSCALE 100
MAXSCALE 1000

	CLASSITEM 'edi_num'
	LABELITEM 'edi_num'

      CLASS
			NAME 'No. de Edificación'
      EXPRESSION /./
         LABEL
           TYPE TRUETYPE
           FONT bluehigh
           SIZE 9
           ANGLE 0
           COLOR 0 153 0 #40 40 40
           BUFFER -1
           POSITION cc
           PARTIALS FALSE
			     FORCE TRUE
         END
      END
END  # END OF LAYERFILE
#----------------------------------------------------------- Edificaciones-Anotación
#----------------------------------------------------------- Objetos_Linea
LAYER
  CONNECTIONTYPE postgis 
  NAME 'objetos_linea'
	GROUP 'Lineas'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from objetos_linea USING UNIQUE oid'
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'objetos_linea'
 WMS_GROUP_TITLE 'Lineas'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

 CLASSITEM 'id'
	
	CLASS 
    NAME 'Plaza'
		EXPRESSION '15'               
		STYLE
		  SYMBOL 'linea'
      COLOR 150 150 150
	    SIZE 1
	  END
	END # end_of_class  
	CLASS 
    NAME 'Canal'
		EXPRESSION '25'               
		STYLE
		  SYMBOL 'linea'
      COLOR 0 0 155
	    SIZE 1
	  END
	END # end_of_class 
	CLASS 
    NAME 'Límite urbáno'
		EXPRESSION '35'               
		STYLE
		  SYMBOL 'linea'
      COLOR 0 0 0
	    SIZE 1
	  END
	END # end_of_class 
END # end of layer object
#----------------------------------------------------------- Objetos_Linea
#----------------------------------------------------------- Objetos_Linea_Servicios
LAYER
  CONNECTIONTYPE postgis 
  NAME 'objetos_linea'
	GROUP 'Servicios'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from objetos_linea USING UNIQUE oid'
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'objetos_linea'
 WMS_GROUP_TITLE 'Servicios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

 CLASSITEM 'id'
	
	CLASS 
    NAME 'Red de Agua'
		EXPRESSION '45'               
		STYLE
		  SYMBOL 'linea'
      COLOR 50 150 255
	    SIZE 1
	  END
	END # end_of_class 
	CLASS 
    NAME 'Red de Electricidad'
		EXPRESSION '55'               
		STYLE
		  SYMBOL 'linea'
      COLOR 255 50 255
	    SIZE 1
	  END
	END # end_of_class 
END # end of layer object
#----------------------------------------------------------- Objetos_Linea_Servicios
#----------------------------------------------------------- Objetos
LAYER
  CONNECTIONTYPE postgis
  NAME 'objetos'
	GROUP 'Elementos especiales'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from objetos USING UNIQUE oid'
  STATUS ON
  TYPE POINT
	
	METADATA
  WMS_SRS 'epsg:32720'
  WMS_TITLE 'objetos'
	WMS_GROUP_TITLE 'Objetos'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  END

 PROJECTION
      	'init=epsg:32720'
 END
   #MINSCALE 1000
   #SYMBOLSCALE 1000
	 CLASSITEM 'id'
   TRANSPARENCY 1000
	  
		CLASS
      NAME 'Horno'
      EXPRESSION '10'
      STYLE
        SYMBOL 'horno'
      END
    END  # END_OF_CLASS	
		CLASS
      NAME 'Letrina'
      EXPRESSION '20'
      STYLE
        SYMBOL 'letrina'
      END
    END  # END_OF_CLASS	
		CLASS
      NAME 'Medidor de Agua'
      EXPRESSION '30'
      STYLE
        SYMBOL 'meda'
      END
    END  # END_OF_CLASS
		CLASS
      NAME 'Noria'
      EXPRESSION '40'
      STYLE
        SYMBOL 'noria'
      END
    END  # END_OF_CLASS
		CLASS
      NAME 'Antena'
      EXPRESSION '50'
      STYLE
        SYMBOL 'antena'
      END
    END  # END_OF_CLASS
		CLASS
      NAME 'Medidor de Viento'
      EXPRESSION '60'
      STYLE
        SYMBOL 'medv'
      END
    END  # END_OF_CLASS			
		CLASS
      NAME 'Cabina de Telefono'
      EXPRESSION '70'
      STYLE
        SYMBOL 'cab'
      END
    END  # END_OF_CLASS		
		CLASS
      NAME 'Poste de Electricidad'
      EXPRESSION '80'
      STYLE
        SYMBOL 'poste'
      END
    END  # END_OF_CLASS		
		CLASS
      NAME 'Punto de Control'
      EXPRESSION '90'
      STYLE
        SYMBOL 'vertice'
      END
    END  # END_OF_CLASS					
END   # END OF LAYERFILE
#----------------------------------------------------------- Objetos
#----------------------------------------------------------- Calles
LAYER
  CONNECTIONTYPE postgis 
  NAME 'Calles'
	GROUP 'Calles'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from calles USING UNIQUE oid'
  TYPE LINE
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Calles'
 WMS_GROUP_TITLE 'Puntos'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

CLASSITEM 'id'
	
    CLASS
     NAME 'Nombre de la Calle'
      EXPRESSION '1'
		 STYLE
		   SYMBOL 'linea'
       COLOR -1 -1 -1
	     SIZE 5
	   END
    END  # CLASS
END # end of layer object
#----------------------------------------------------------- Calles
#----------------------------------------------------------- Calles-Anotacion
LAYER
  CONNECTIONTYPE postgis 
  NAME 'Calles_Anot'
	GROUP 'Calles'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from calles USING UNIQUE oid'
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'calles_anot'
 WMS_GROUP_TITLE 'Puntos'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

CLASSITEM 'id'
LABELITEM 'nombre'
	
    CLASS
     NAME 'Nombre'
     EXPRESSION '0'
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 7
       ANGLE AUTO
       COLOR 0 0 0
			 #OUTLINECOLOR 255 0 0
       BUFFER 3
			 OFFSET 0 0
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS
    CLASS
     NAME 'Nombre_Grande'
     EXPRESSION '2'
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 10
       ANGLE AUTO
       COLOR 255 255 255
			 OUTLINECOLOR 0 0 0
       BUFFER 3
			 OFFSET 0 0
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS	
END # end of layer object
#----------------------------------------------------------- Calles_Anotacion
#----------------------------------------------------------- Flecha Norte
LAYER
    NAME 'n_arrow'
		GROUP 'Manzanos'
    STATUS ON
    TRANSFORM FALSE
    TYPE POINT
		METADATA
      WMS_TITLE 'North Arrow'
			WMS_GROUP_TITLE 'North Arrow'
      WMS_FEATURE_INFO_MIME_TYPE 'text/html'
    END
		
		TRANSPARENCY 1000	
		
    FEATURE
      POINTS 390 30 END
    END
    CLASS
      STYLE
        SYMBOL 'n_arrow'
      END
    END
END
#----------------------------------------------------------- Flecha Norte
END  # MAPFILE>
";
################################################################################
#------------------- CHEQUEAR SI SE PUEDE ABRIR EL ARCHIVO --------------------#
################################################################################	
if (!$handle = fopen($filename, "w")) {
   $error = 2; 
}
if (!fwrite($handle, $content)) {
   $error = 3; 
}
fclose($handle);

?>