<?php

################################################################################
#----------------------- RUTA Y NOMBRE PARA GRABAR ----------------------------#
################################################################################	

$filename = "C:/apache/siicat/mapa/igm_planocatastral.map";
 
################################################################################
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "MAP
#
#< Start of map file - created $fecha - $hora
#
NAME 'IGM_PLANOCATASTRAL'
STATUS ON

PROJECTION
			'init=epsg:32720'     # UTM 20, Zona 20S, WGS 1984
END

SIZE 1200 1200
#EXTENT 423000 8086700 425500 8089200
EXTENT 599500 8211500 608500 8220500
UNITS  meters
SYMBOLSET 'c:/apache/siicat/mapa/symbols/symbset.sym'
FONTSET   'c:/apache/siicat/mapa/fonts/fonts.fnt'
IMAGECOLOR 255 255 255

#
# Start of web interface definition
#
WEB
 LOG igm.log
 TEMPLATE igm_planocatastral.html
 IMAGEPATH 'c:/apache/htdocs/tmp/'
 IMAGEURL 'http://$server/tmp/'
 EMPTY 'http://$server/$folder/nada.html'
 
 METADATA
  WMS_ONLINERESOURCE 'http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/igm_planopredial.map'
  WMS_SRS 'epsg:32720'
  WMS_ACCESSCONSTRAINTS 'none'
  WMS_TITLE 'IGM_PREDIO'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  WMS_ABSTRACT ''
 END  #METADATA

END  #HEADER

#OUTPUTFORMAT
#  NAME 'png'
#	DRIVER 'GDAL/PNG'
#	MIMETYPE 'image/png'
#	IMAGEMODE RGB
#	EXTENSION 'png'
#	FORMATOPTION 'QUALITY=100'
#END

OUTPUTFORMAT
    NAME 'AGG'
    DRIVER 'AGG/PNG'
    MIMETYPE 'image/png'
    IMAGEMODE RGB
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

#
# Start of reference map
#
REFERENCE
  STATUS OFF
  IMAGE 'c:/apache/htdocs/$folder/data/reference.gif'
  SIZE 120 120
  EXTENT -70.254 -23.569 -56.844 -8.994
  COLOR -1 -1 -1
  OUTLINECOLOR 255 0 0
END  #REFERENCE

#
# Start of legend
#
LEGEND
  STATUS OFF
  KEYSIZE 16 8
  TEMPLATE 'legend.html'
  LABEL
    COLOR 120 120 120
  END # ENDE LABEL
END   #LEGEND

#
# Start of scalebar
#
SCALEBAR
 STATUS OFF
 POSITION ll
 STYLE 0
 INTERVALS 6
 IMAGECOLOR 255 255 255
 LABEL
  COLOR 0 0 0
  SIZE GIANT  #SMALL
 END  #ENDE LABEL
 #SIZE 150 2
 #SIZE 304 4   #Mapsize 700+700
 Size 700 8    #Mapsize 1400+1400
 TRANSPARENT OFF
 COLOR 0 0 0
 BACKGROUNDCOLOR 255 255 255
 OUTLINECOLOR 100 100 100
 UNITS METERS
END   #SCALEBAR

#
# Start of layer definitions #
#
#----------------------------------------------------------- U.V.s
LAYER
  CONNECTIONTYPE postgis
  NAME 'Unidades Vecinales'
  GROUP 'Manzanos'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_poly USING UNIQUE oid'
  STATUS ON
  TYPE LINE
	#TEMPLATE 'query_manzanos.html'
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
       NAME 'U.V.'
			 EXPRESSION (('[numero]' == '115' ) AND ('[user_id]' == '$user_id' ))
			 STYLE
			    SYMBOL 'Hauptstrasse'
          COLOR 0 0 0
          #OUTLINECOLOR 0 0 0 #40 40 40
			    SIZE 0
			 END
      END  # CLASS
			 
END  # END OF LAYERFILE		
#----------------------------------------------------------- U.V.s
#----------------------------------------------------------- Predio-Outline
LAYER
  CONNECTIONTYPE postgis
  NAME 'predios'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from predios_ocha USING UNIQUE oid'	
  TYPE Polygon
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
      OUTLINECOLOR 50 50 50 #40 40 40			
	    SIZE 4
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Predio-Outline
#----------------------------------------------------------- Predio-Seleccionado
LAYER
  CONNECTIONTYPE postgis
  NAME 'predio_select'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_poly USING UNIQUE oid'	
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Predio_Select'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

 CLASSITEM 'numero'
	CLASS 
    NAME 'Delimitación' 
		EXPRESSION (('[numero]' == '58' ) AND ('[user_id]' == '$user_id' ))             
		STYLE
		  SYMBOL 'gestrichelt3'
      COLOR -1 -1 -1
      OUTLINECOLOR 0 0 0 #40 40 40			
	    SIZE 2
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Predio-Seleccionado
#----------------------------------------------------------- Predio-Seleccionado con ocha
LAYER
  CONNECTIONTYPE postgis
  NAME 'predio_select'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_poly USING UNIQUE oid'	
  TYPE Polygon
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Predio_Select'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

 CLASSITEM 'numero'
	CLASS 
    NAME 'Delimitación' 
		EXPRESSION '55'             
		STYLE
		  SYMBOL 'circle'
      COLOR -1 -1 -1
      OUTLINECOLOR 0 0 0 #40 40 40			
	    SIZE 4
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Predio-Seleccionado con ocha
#----------------------------------------------------------- Predio_Colindantes_Anot
LAYER
  CONNECTIONTYPE postgis
  NAME 'colindantes'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_poly USING UNIQUE oid'	
  STATUS ON
  TYPE ANNOTATION
METADATA
 'WMS_SRS' 'epsg:32720'
 WMS_TITLE 'colindantes'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

	CLASSITEM 'numero'
	LABELITEM 'label'
	#LABELITEM 'cod_cat'

      CLASS
			NAME 'Nombre'
      EXPRESSION '66'
         LABEL
           TYPE TRUETYPE
           #FONT bluehigh
					 FONT tahoma
           SIZE 16
           ANGLE 0
           COLOR 0 0 0 #40 40 40
           BUFFER -1
 			     ANTIALIAS TRUE		 
           POSITION cc
           PARTIALS FALSE
			     FORCE TRUE
					 WRAP '@'
         END
      END
END  # END OF LAYERFILE
#----------------------------------------------------------- Predio_Colindantes_Anot
#----------------------------------------------------------- Edificaciones-Seleccionadas
LAYER
  CONNECTIONTYPE postgis
  NAME 'edif_select'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_poly USING UNIQUE oid'	
  TYPE Polygon
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Edif_Select'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

 CLASSITEM 'numero'
	CLASS 
    NAME 'Delimitación' 
		EXPRESSION '44'             
		STYLE
		  SYMBOL 'circle'
      COLOR 200 200 200
      OUTLINECOLOR 0 0 0 #40 40 40			
	    SIZE 4
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Edificaciones-Seleccionadas
#----------------------------------------------------------- Edificaciones-Seleccionadas-Anotación
LAYER
  CONNECTIONTYPE postgis
  NAME 'edificiones'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_poly USING UNIQUE oid'	
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

	CLASSITEM 'numero'
	LABELITEM 'edi_num'

      CLASS
			NAME 'No. de Edificación'
      EXPRESSION '44'
         LABEL
           TYPE TRUETYPE
           FONT tahoma
           SIZE 20
           ANGLE 0
           COLOR 0 0 0 #40 40 40
           BUFFER -1
           POSITION cc
           PARTIALS FALSE
			     FORCE TRUE
         END
      END
END  # END OF LAYERFILE
#----------------------------------------------------------- Edificaciones-Seleccionadas-Anotación
#----------------------------------------------------------- Radio_Ochaves
LAYER
  CONNECTIONTYPE postgis 
  NAME 'Radio_Ochaves'
	GROUP 'Puntos'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_line USING UNIQUE oid'
  TYPE LINE
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Radio_Ochaves'
 WMS_GROUP_TITLE 'Puntos'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
#      	'init=epsg:4326'
END	

CLASSITEM 'id'
	
    CLASS
     NAME 'Calle'
      EXPRESSION (( '[id]' == '99' ) AND ('[user_id]' == '$user_id' ))
		 STYLE
		   SYMBOL 'gestrichelt2'
       COLOR 0 0 0
	     SIZE 2
	   END
    END  # CLASS	
END # end of layer object
#----------------------------------------------------------- Radio_Ochaves
#----------------------------------------------------------- Radio_Ochaves-Anotacion
LAYER
  CONNECTIONTYPE postgis 
  NAME 'Calles_Anot'
	GROUP 'Puntos'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_line USING UNIQUE oid'
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
LABELITEM 'tipo'
	
    CLASS
     NAME 'Nombre'
     EXPRESSION (( '[id]' == '99' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 14
       ANGLE 0
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
END # end of layer object
#----------------------------------------------------------- Radio_Ochaves_Anotacion
#----------------------------------------------------------- Points
LAYER
  CONNECTIONTYPE postgis
  NAME 'points'
	GROUP 'Puntos'
  CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_point USING UNIQUE oid'
  STATUS ON
  TYPE POINT
	
	METADATA
  WMS_SRS 'epsg:32720'
  WMS_TITLE 'POINTS'
	WMS_GROUP_TITLE 'Puntos'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  END

 PROJECTION
      	'init=epsg:32720'
 END
	 CLASSITEM 'pos'
   TRANSPARENCY 1000
	  
		CLASS
      NAME 'Centroid'
      EXPRESSION (( '[pos]' == 'CEN' ) OR ('[pos]' == 'NN' ))
      STYLE
        SYMBOL 'circle'
				COLOR -1 -1 -1
				SIZE 0
      END
    END  # END_OF_CLASS	
		CLASS
      NAME 'TEXT'
      EXPRESSION ((( '[pos]' == 'NO' )OR('[pos]' == 'NE' )OR( '[pos]' == 'SO' )OR('[pos]' == 'SE' )OR('[pos]' == '--' )) AND ('[user_id]' == 'AAA' )) 
      STYLE
         #SYMBOL 'vertice'
        SYMBOL 'circle'
				COLOR -1 -1 -1
				SIZE 0
      END
    END  # END_OF_CLASS


END   # END OF LAYERFILE
#----------------------------------------------------------- Points
#----------------------------------------------------------- Points-Anotation
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'points_anot'
	GROUP 'Puntos'
  CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_point USING UNIQUE oid'
  STATUS ON
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'points_anot'
 WMS_GROUP_TITLE 'Puntos'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

  CLASSITEM 'pos'
  LABELITEM 'text'
 
    CLASS
     NAME 'SO'
     EXPRESSION (( '[pos]' == 'SO' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 16
       ANGLE 0
       COLOR 0 0 0
       BUFFER 3
			 OFFSET 5 5
       POSITION ll
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS
		CLASS
     NAME 'NO'
     EXPRESSION (( '[pos]' == 'NO' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 16
       ANGLE 0
       COLOR 0 0 0
       BUFFER 3
			 OFFSET 5 5
       POSITION ul
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS
		CLASS
     NAME 'SE'
     EXPRESSION (( '[pos]' == 'SE' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 16
       ANGLE 0
       COLOR 0 0 0
       BUFFER 3
			 OFFSET 5 5
       POSITION lr
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS
		CLASS
     NAME 'NE'
     EXPRESSION (( '[pos]' == 'NE' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 16
       ANGLE 0
       COLOR 0 0 0
       BUFFER 3
			 OFFSET 5 5
       POSITION ur
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS
		CLASS
    NAME 'INTERMEDIOS'
     EXPRESSION (( '[pos]' == '--' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 16
       ANGLE 0
       COLOR 0 0 255
       BUFFER 3
			 OFFSET 10 10
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
    END  # CLASS		 
		CLASS
    NAME 'CEN'
     EXPRESSION (( '[pos]' == 'CEN' ) AND ('[user_id]' == '$user_id' ))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 16
       ANGLE 0
       COLOR 0 0 255
       BUFFER 3
			 OFFSET 0 0
       POSITION cc
       PARTIALS TRUE
			 FORCE TRUE
			 ANTIALIAS TRUE
      END
     END  # CLASS
				 
END  # END OF LAYERFILE
#----------------------------------------------------------- Points-Anotation
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
 WMS_GROUP_TITLE 'Calles'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	
	
 CLASSITEM 'id'
 
	CLASS 
    NAME 'Calles'
		EXPRESSION '0'
		STYLE
		  SYMBOL 'Hauptstrasse'
      COLOR -1 -1 -1
	    SIZE 4
	  END
  END

END # end of layer object
#----------------------------------------------------------- Calles
#----------------------------------------------------------- Calles-Anotation
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
 WMS_TITLE 'Calles_Anot'
 WMS_GROUP_TITLE 'Calles'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

#MAXSCALE 60000
 CLASSITEM 'id'
 LABELITEM 'nombre'
 
	CLASS 
	 NAME 'Calles'
     EXPRESSION '0' 
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 22
       ANGLE AUTO
       COLOR 255 255 255   # orange: 255 153 51
			 OUTLINECOLOR 0 0 0
       BUFFER 3
 			 ANTIALIAS TRUE
       POSITION cc
       PARTIALS FALSE
			 FORCE TRUE
      END
  END
END # end of layer object
#----------------------------------------------------------- Calles-Anotation
#----------------------------------------------------------- Longitud de Linea
LAYER  # START OF ANNOTATION LAYERFILE
  CONNECTIONTYPE postgis
  NAME 'Longitud_Lineas'
	GROUP 'Calles'
	STATUS ON
  CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_line USING UNIQUE oid'
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Longitud_Lineas'
 WMS_GROUP_TITLE 'Calles'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END

  CLASSITEM 'nombre'
  LABELITEM 'nombre'
 
    CLASS
     NAME 'Longitud'
     EXPRESSION (('[nombre]' > '0') AND ('[user_id]' == '$user_id')) 
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       #SIZE 7
			 SIZE 16
       ANGLE AUTO
       COLOR 0 0 0
     #  BUFFER 15
       POSITION lc
       PARTIALS FALSE
			 FORCE TRUE
			 ANTIALIAS TRUE 
      END
     END  # CLASS
		 
END  # END OF LAYERFILE
#----------------------------------------------------------- Longitud de Linea
#----------------------------------------------------------- Objetos_Linea
LAYER
  DEBUG ON
  CONNECTIONTYPE postgis 
  NAME 'objetos_linea'
	GROUP 'Calles'
	STATUS ON
  CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from objetos_linea USING UNIQUE oid'
  TYPE LINE
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'objetos_linea'
 WMS_GROUP_TITLE 'Calles'
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
	    SIZE 2
	  END
	END # end_of_class  
	CLASS 
    NAME 'Canal'
		EXPRESSION '25'               
		STYLE
		  SYMBOL 'linea'
      COLOR -1 -1 -1
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
#----------------------------------------------------------- Objetos_Linea-Anotation
LAYER 
  CONNECTIONTYPE postgis
  NAME 'Objetos_linea_Anot'
	GROUP 'Calles'
	STATUS ON
  CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from objetos_linea USING UNIQUE oid'	
  TYPE ANNOTATION

METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Objetos_linea_Anot'
 WMS_GROUP_TITLE 'Calles'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

#MAXSCALE 60000
 CLASSITEM 'id'
 LABELITEM 'descrip'
 
	CLASS 
	 NAME 'Canal'
     EXPRESSION '25' 
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 22
       ANGLE AUTO
       COLOR 255 255 255   # orange: 255 153 51
			 OUTLINECOLOR 0 0 0
       BUFFER 3
 			 ANTIALIAS TRUE
       POSITION cc
       PARTIALS FALSE
			 FORCE TRUE
      END
  END
END # end of layer object
#----------------------------------------------------------- Objetos_Linea-Anotation
#----------------------------------------------------------- North Arrow
LAYER
    NAME 'n_arrow'
	  GROUP 'Predios'		
    STATUS ON
    TRANSFORM FALSE
    TYPE POINT
		POSTLABELCACHE TRUE	
		
    TRANSPARENCY 1000	
					
    FEATURE
      POINTS 1360 80 END			 
    END
    CLASS
      STYLE
        SYMBOL 'n_arrow_grande'
      END
    END
END
#----------------------------------------------------------- North Arrow

END  # MAPFILE
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