<?php

$filename = "C:/apache/siicat/mapa/siicat_zoom.map";

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
#------------------------ CREAR COLORES PARA LAS ZONAS ------------------------#
################################################################################	
$sql="SELECT DISTINCT zona FROM zonas ORDER BY zona";
$numero_de_zonas = pg_num_rows(pg_query($sql));
$i = 0; $r = $g = $b = 230;
$delta = ROUND (150/$numero_de_zonas,0);
$result_z = pg_query($sql);
while ($line = pg_fetch_array($result_z, null, PGSQL_ASSOC)) {			 	 			           
   foreach ($line as $col_value) {
      $zona[$i] = $col_value;
			$zona_color[$i] = "$r $g $b";
			$r = $g = $b = $r - $delta;	
			$i++;
	 }
}
pg_free_result($result_z); 
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
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "MAP
#
# Start of map file - created $fecha - $hora
#
NAME 'igm_zoom'
STATUS ON

PROJECTION
     	'init=epsg:32720'
END

SIZE   600 600
# EXTENT $xmin $ymin $xmax $ymax
# $mapfile_extent = 382109 384375 7954052 7956657 

EXTENT 382109 384375 7954052 7956657
UNITS  meters
SYMBOLSET 'c:/apache/siicat/mapa/symbols/symbset.sym'
FONTSET   'c:/apache/siicat/mapa/fonts/fonts.fnt'
IMAGECOLOR 255 255 255

#
# Start of web interface definition
#
WEB
 LOG siicat_zoom.log
 TEMPLATE siicat_zoom.html
 IMAGEPATH 'c:/apache/htdocs/tmp/'
 IMAGEURL 'http://$server/tmp/'
 EMPTY 'http://$server/$folder/nada.html'
 MINSCALEDENOM 100
 MAXSCALEDENOM 100000
# change this value to match your setup

 METADATA
  WMS_ONLINERESOURCE 'http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat.map'
  WMS_SRS 'epsg:32720'
  WMS_ACCESSCONSTRAINTS 'none'
  WMS_TITLE 'SIICAT'
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

SYMBOL
   NAME 'circle'
   TYPE ellipse
   FILLED true
   POINTS
     1 1
   END
END

#----------------------------------------------------------- MAPA EN TEMPLATE DE CONSULTA
QUERYMAP
  SIZE 600 600
  STATUS ON #OFF
  STYLE HILITE
  COLOR 255 0 0
END
#----------------------------------------------------------- MAPA EN TEMPLATE DE CONSULTA
#----------------------------------------------------------- MAPA DE UBICACION
REFERENCE
  STATUS ON
  IMAGE datos/reference.png
  SIZE 60 60
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
#----------------------------------------------------------- Zonas Homogeneas
LAYER
  CONNECTIONTYPE postgis
  NAME 'Zonas'
  GROUP 'Zonas homogeneas'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from zonas USING UNIQUE oid'
  STATUS ON
  TYPE Polygon
#  TEMPLATE 'catbr_query_manzanos.htm'	
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Zonas'
 WMS_GROUP_TITLE 'Zonas homogeneas'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

    CLASSITEM 'zona'";
$i = 0;
while ($i < $numero_de_zonas) {
$content = $content."
											
		  CLASS
       NAME 'Zona $zona[$i]'
			 EXPRESSION '$zona[$i]'
			 STYLE
          COLOR $zona_color[$i]
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
#----------------------------------------------------------- Zonas Homogeneas
#----------------------------------------------------------- Z.H.-Anotation
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'Zonas'
  GROUP 'Zonas homogeneas'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from zonas USING UNIQUE oid'
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

  CLASSITEM 'zona'
  LABELITEM 'zona'		 
		CLASS
     NAME 'Zonas'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 13
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
#----------------------------------------------------------- Z.H.-Anotation
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
#----------------------------------------------------------- U.V.s
LAYER
  CONNECTIONTYPE postgis
  NAME 'Unidades Vecinales'
  GROUP 'Manzanos'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from manzanos USING UNIQUE oid'
  STATUS ON
  TYPE Polygon
  TEMPLATE 'siicat_query_manzanos.htm'	
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Poly'
 WMS_GROUP_TITLE 'Poligono'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

    CLASSITEM 'cod_uv'

      CLASS
       NAME 'U.V. 1'
			 EXPRESSION '1'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 165 165
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
      END  # CLASS
      
			CLASS
       NAME 'U.V. 2'
			 EXPRESSION '2'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 195 252 255
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
      END  # CLASS
			
			CLASS
       NAME 'U.V. 3'
			 EXPRESSION '3'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 155 255 155
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	 
			 
			CLASS
       NAME 'U.V. 4'
			 EXPRESSION '4'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 193 193 193
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END		 
      END  # CLASS		
			
		  CLASS
       NAME 'U.V. 5'
			 EXPRESSION '5'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 249 165
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			
			  CLASS
       NAME 'U.V. 6'
			 EXPRESSION '6'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 255 199 174
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			
		  CLASS
       NAME 'U.V. 7'
			 EXPRESSION '7'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 183 223 134
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	
			
		  CLASS
       NAME 'U.V. 8'
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
       NAME 'U.V. $cod_uv_nuevo[$i]'
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
          COLOR 230 230 230
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS		
			 
END  # END OF LAYERFILE		
#----------------------------------------------------------- U.V.s
#----------------------------------------------------------- Manzano-Anotation
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'manzanos'
	GROUP 'Manzanos'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from manzanos USING UNIQUE oid'
  STATUS ON
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'poly_anot'
 WMS_GROUP_TITLE 'Poligono'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

 MINSCALEDENOM 100
 MAXSCALEDENOM 10000

PROJECTION
     	'init=epsg:32720'			
END

  CLASSITEM 'cod_man'
  LABELITEM 'cod_man'		 
		CLASS
     NAME 'Manzanos'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 13
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
#----------------------------------------------------------- Manzano-Anotation
#----------------------------------------------------------- Predios
LAYER
  CONNECTIONTYPE postgis
  NAME 'predios'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from predios USING UNIQUE oid'	
  STATUS OFF
  TYPE Polygon

METADATA
 'WMS_SRS' 'epsg:32720'
 WMS_TITLE 'Predios'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

  CLASSITEM 'cod_uv'

      CLASS
       NAME 'Delimitación'
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
  CONNECTIONTYPE postgis
  NAME 'predios'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from predios USING UNIQUE oid'	
  STATUS OFF
  TYPE ANNOTATION
METADATA
 'WMS_SRS' 'epsg:32720'
 WMS_TITLE 'Predios'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

  MINSCALEDENOM 100
	MAXSCALEDENOM 2500
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
 'WMS_SRS' 'epsg:32720'
 WMS_TITLE 'Edificaciones'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

MINSCALEDENOM 100
MAXSCALEDENOM 1500

  CLASSITEM 'id_edif'

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
 'WMS_SRS' 'epsg:32720'
 WMS_TITLE 'Edificaciones'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

MINSCALEDENOM 100
MAXSCALEDENOM 1500

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
	TEMPLATE 'catbr_query_objetos.htm'
  TOLERANCE 10
  TOLERANCEUNITS meters
	
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
#----------------------------------------------------------- Objetos
LAYER
  CONNECTIONTYPE postgis
  NAME 'objetos'
	GROUP 'Elementos especiales'
  CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from objetos USING UNIQUE oid'
  STATUS ON
  TYPE POINT
	TEMPLATE 'catbr_query_objetos.htm'
  TOLERANCE 10
  TOLERANCEUNITS meters

	METADATA
  WMS_SRS 'epsg:32720'
  WMS_TITLE 'objetos'
	WMS_GROUP_TITLE 'Elementos Especiales'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  END

 PROJECTION
      	'init=epsg:32720'
 END
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
      NAME 'Poste Luz GPS'
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
				SIZE 10
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
	TEMPLATE 'catbr_query_calles.htm'
  TOLERANCE 5
  TOLERANCEUNITS meters
		
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
     NAME 'Calle'
      EXPRESSION '0'
		 STYLE
		   SYMBOL 'linea'
       COLOR 200 200 200
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
    STATUS ON
		GROUP 'Manzanos'
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