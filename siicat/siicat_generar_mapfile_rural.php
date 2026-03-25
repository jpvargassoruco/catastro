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
#----------------------- RUTA Y NOMBRE PARA GRABAR ----------------------------#
################################################################################	

$filename = "C:/apache/siicat/mapa/siicat_rural.map";
 
################################################################################
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "MAP
#
# Start of map file - created $fecha - $hora
#
NAME 'SIICAT_RURAL'
STATUS ON

PROJECTION
     	'init=epsg:32720'
#	    'init=epsg:4326'		
END

SIZE   400 400
#EXTENT 423000 8086700 425500 8089200   (BR)
#EXTENT 599500 8211500 608500 8220500   (CONCE)
EXTENT 569500 8181500 638500 8250500
UNITS  meters
#EXTENT -63.72 -17.3 -63.71 -17.29
#UNITS  dd

SYMBOLSET 'c:/apache/siicat/mapa/symbols/symbset.sym'
FONTSET   'c:/apache/siicat/mapa/fonts/fonts.fnt'
IMAGECOLOR 255 255 255
#IMAGECOLOR 244 255 228

#
# Start of web interface definition
#
WEB
 LOG siicat.log
 TEMPLATE siicat_rural.html
 IMAGEPATH 'c:/apache/htdocs/tmp/'
 IMAGEURL 'http://$server/tmp/'
 EMPTY 'http://$server/$folder/nada.html'
 MINSCALEDENOM 10000
 MAXSCALEDENOM 2500000

 METADATA
  WMS_ONLINERESOURCE 'http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/siicat_rural.map'
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
  IMAGE datos/reference3.png
  SIZE 120 120
  #EXTENT 423000 8086500 425750 8089250
  # EXTENT 569500 8181500 638500 8250500
  EXTENT 439500 8141500 708500 8410500		
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
##----------------------------------------------------------- Imagen Satelital Nueva
LAYER
  NAME 'landsat'
  GROUP 'Imagen Satelital'
  TYPE RASTER
  STATUS ON	
  DATA 'c:/apache/siicat/data/p230r071_20100720_b345_cut.tif'

PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     	#'init=epsg:4326'
END

END # end of layer file
#----------------------------------------------------------- Imagen Satelital Nueva
#----------------------------------------------------------- Municipio
LAYER
  NAME 'muni'
	GROUP 'Division_Politica'
	STATUS ON
  DATA 'C:/apache/siicat/data/ccp_limite'
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'muni'
 WMS_GROUP_TITLE 'Division_Politica'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     #	'init=epsg:4326'
END	

 CLASSITEM 'nom_muni'

	CLASS 
    NAME 'Limite municipal'
		EXPRESSION /./              
		STYLE
		  SYMBOL 'circle'
      COLOR -1 -1 -1
			OUTLINECOLOR 255 0 0
	    SIZE 1
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Municipio
#----------------------------------------------------------- Municipio_Anot
LAYER
  NAME 'muni_anot'
	GROUP 'Division_PoliticaOFF'
	STATUS ON
  DATA 'C:/apache/siicat/data/ccp_limite'
  TYPE ANNOTATION 
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'muni_anot'
 WMS_GROUP_TITLE 'Division_Politica'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     	#'init=epsg:4326'
END	

 CLASSITEM 'nom_muni'
 LABELITEM 'nom_muni'
  CLASS
     NAME 'Nombre'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 20
       ANGLE 0
       COLOR 255 0 0
       BUFFER 20
			 OFFSET 0 0
       #BACKGROUNDCOLOR 0 0 0
			 # OUTLINECOLOR 0 0 0
       POSITION lc
       PARTIALS TRUE
			 FORCE FALSE
			 ANTIALIAS TRUE
      END
     END  # CLASS
END # end of layer object
#----------------------------------------------------------- Municipio_Anot
#----------------------------------------------------------- Departamento
LAYER
  NAME 'depart'
	GROUP 'Division_Politica'
	STATUS ON
  DATA 'C:/apache/siicat/data/limite_dep'
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'depart'
 WMS_GROUP_TITLE 'Division_Politica'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     #	'init=epsg:4326'
END	

 CLASSITEM 'ID'

	CLASS 
    NAME 'Limite departamental'
		EXPRESSION /./              
		STYLE
		  SYMBOL 'circle'
      COLOR 51 51 51
	    SIZE 2
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Departamento
#----------------------------------------------------------- Departamento_Anot
LAYER
  NAME 'depart_anot'
	GROUP 'Division_PoliticaOFF'
	STATUS ON
  DATA 'C:/apache/siicat/data/limite_dep'
  TYPE ANNOTATION 
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'depart_anot'
 WMS_GROUP_TITLE 'Division_Politica'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     	#'init=epsg:4326'
END	

 CLASSITEM 'Id'
 LABELITEM 'Id'
  CLASS
     NAME 'Nombre'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 12
       ANGLE AUTO
       COLOR 51 51 51
       BUFFER 10
			 OFFSET 0 0
       #BACKGROUNDCOLOR 0 0 0
			 # OUTLINECOLOR 0 0 0
       POSITION lc
       PARTIALS TRUE
			 FORCE FALSE
			 ANTIALIAS TRUE
      END
     END  # CLASS
END # end of layer object
#----------------------------------------------------------- Departamento_Anot
#----------------------------------------------------------- Predios
LAYER
  NAME 'predios'
	GROUP 'Predios'
	STATUS ON
  DATA 'C:/apache/siicat/data/san_inra'
  TYPE POLYGON
	TEMPLATE 'siicat_query_predios_rural.htm'	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'predios'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     #	'init=epsg:4326'
END	

 CLASSITEM 'tipopred'

	CLASS 
    NAME 'Privado'
		EXPRESSION (('[tipopred]' == '1') OR ('[tipopred]' == '2'))              
		STYLE
		  SYMBOL 'circle'
      COLOR -1 -1 -1
			OUTLINECOLOR 0 0 0
	    SIZE 1
	  END
	END # end_of_class
	CLASS 
    NAME 'Fiscal'
		EXPRESSION '5'              
		STYLE
		  SYMBOL 'circle'
      COLOR -1 -1 -1
			OUTLINECOLOR 50 50 50
	    SIZE 1
	  END
	END # end_of_class	
END # end of layer object
#----------------------------------------------------------- Predios
#----------------------------------------------------------- Predios_Anot
LAYER
  NAME 'predios_anot'
	GROUP 'Predios'
	STATUS ON
  DATA 'C:/apache/siicat/data/san_inra'
  TYPE ANNOTATION 
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'predios_anot'
 WMS_GROUP_TITLE 'Division_Politica'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
MINSCALEDENOM 100
MAXSCALEDENOM 120000		
	
PROJECTION
			'init=epsg:32720'     # UTM, Zona 20S, WGS 1984	
     	#'init=epsg:4326'
END	

 CLASSITEM 'tipopred'
 LABELITEM 'nompred'
  CLASS
     NAME 'Nombre'
     EXPRESSION /./
      LABEL
       TYPE TRUETYPE
       FONT arial
       SIZE 7
       ANGLE 0
       COLOR 0 0 0
       BUFFER 20
			 OFFSET 0 0
       #BACKGROUNDCOLOR 0 0 0
			 # OUTLINECOLOR 0 0 0
       POSITION lc
       PARTIALS TRUE
			 FORCE FALSE
			 ANTIALIAS TRUE
      END
     END  # CLASS
END # end of layer object
#----------------------------------------------------------- Predios_Anot
#----------------------------------------------------------- Caminos
LAYER
  NAME 'caminos'
	GROUP 'Red de vias'
	STATUS ON
  DATA 'C:/apache/siicat/data/caminos_ult'
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'caminos_ult'
 WMS_GROUP_TITLE 'Lineas'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

 CLASSITEM 'id'
	
	CLASS 
    NAME 'Red Nacional'
		EXPRESSION (('[id]' == '1') OR ('[id]' == '2') OR ('[id]' == '3'))         
		STYLE
		  SYMBOL 'linea'
      COLOR 0 0 0
	    SIZE 2
	  END
	END # end_of_class  
	CLASS 
    NAME 'Red Departamental'
		EXPRESSION '4'               
		STYLE
		  SYMBOL 'linea'
      COLOR 40 40 40
	    SIZE 1
	  END
	END # end_of_class 
	CLASS 
    NAME 'Red Municipal-Vecinal'
		EXPRESSION '5'               
		STYLE
		  SYMBOL 'linea'
      COLOR 80 80 80
	    SIZE 1
	  END
	END # end_of_class 	
	CLASS 
    NAME 'Camino privado'
		EXPRESSION '6'               
		STYLE
		  SYMBOL 'linea'
      COLOR 120 120 120
	    SIZE 1
	  END
	END # end_of_class
	CLASS 
    NAME 'Senda'
		EXPRESSION '7'               
		STYLE
		  SYMBOL 'linea'
      COLOR 160 160 160
	    SIZE 1
	  END
	END # end_of_class 	 
END # end of layer object
#----------------------------------------------------------- Caminos
#----------------------------------------------------------- Flecha Norte
LAYER
    NAME 'n_arrow'
		GROUP 'Division_Politica'
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