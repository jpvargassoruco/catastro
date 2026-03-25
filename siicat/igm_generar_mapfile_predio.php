<?php

################################################################################
#----------------------- RUTA Y NOMBRE PARA GRABAR ----------------------------#
################################################################################	

$filename = "C:/apache/siicat/mapa/igm_predios.map";

################################################################################
#----------------------- BUSCAR NOMBRES DE BARRIOS ----------------------------#
################################################################################	


################################################################################
#------------------- PREPARAR CONTENIDO PARA GRABAR ---------------------------#
################################################################################	
$content = "MAP 
#
#< Start of map file - created $fecha - $hora
#
NAME 'igm_Predio'
STATUS ON

PROJECTION
			'init=epsg:32720'     # UTM 20, Zona 20S, WGS 1984
END

SIZE 1200 1200
EXTENT $mapfile_extent
UNITS  meters
SYMBOLSET 'c:/apache/siicat/mapa/symbols/symbset.sym'
FONTSET   'c:/apache/siicat/mapa/fonts/fonts.fnt'
IMAGECOLOR 255 255 255   #244 255 228

#
# Start of web interface definition
#
WEB
 LOG igm.log
 TEMPLATE igm_predios.html
 IMAGEPATH 'c:/apache/htdocs/tmp/'
 IMAGEURL 'http://$server/tmp/'
 EMPTY 'http://$server/$folder/nada.html'
 
 METADATA
  WMS_ONLINERESOURCE 'http://$server/cgi-bin/mapserv.exe?map=c:/apache/siicat/mapa/igm.map'
  WMS_SRS 'epsg:32720'
  WMS_ACCESSCONSTRAINTS 'none'
  WMS_TITLE 'IGM_PREDIO'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
  WMS_ABSTRACT ''
 END  #METADATA

END  #HEADER

OUTPUTFORMAT
  NAME 'png'
	DRIVER 'GDAL/PNG'
	MIMETYPE 'image/png'
	IMAGEMODE RGB
	EXTENSION 'png'
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
  IMAGE 'datos/reference.png'
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
 INTERVALS 5
 IMAGECOLOR 255 255 255
 LABEL
  COLOR 0 0 0
  SIZE GIANT  #SMALL
 END  #ENDE LABEL
 #SIZE 150 2
 #SIZE 304 4   #Mapsize 700+700
 Size 600 8    #Mapsize 1400+1400
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
  DATA 'the_geom from manzanos USING UNIQUE oid'
  STATUS ON
  TYPE Polygon
	TEMPLATE 'igm_query_manzanos.html'
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
          COLOR 192 252 255
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
      END  # CLASS
      
			CLASS
       NAME 'U.V. 2'
			 EXPRESSION '2'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 155 255 155
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
      END  # CLASS
			
			CLASS
       NAME 'U.V. 3'
			 EXPRESSION '3'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 193 193 193
          OUTLINECOLOR 0 0 153 #40 40 40
			    SIZE 2
			 END
			END  # CLASS	 
			 
			CLASS
       NAME 'U.V. 4'
			 EXPRESSION '4'
			 STYLE
			    #SYMBOL 'circle'
          COLOR 230 230 203
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
			END  # CLASS									
			 	 											
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
       BUFFER 0
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
      OUTLINECOLOR 0 0 153 #40 40 40			
	    SIZE 2
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
  TYPE Polygon
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Predio_Select'
 WMS_GROUP_TITLE 'Predios'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION 	'init=epsg:32720' END	

 CLASSITEM 'numero'
	CLASS 
    NAME 'Delimitación' 
		EXPRESSION (('[numero]' == '55') AND ('[user_id]' == '$user_id'))            
		STYLE
		  SYMBOL 'circle'
      COLOR -1 -1 -1
      OUTLINECOLOR 255 30 0 #40 40 40			
	    SIZE 2
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Predio-Seleccionado
#----------------------------------------------------------- Predio-Anotación
LAYER
  CONNECTIONTYPE postgis
  NAME 'predios_anot'
	GROUP 'Predios'
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from predios USING UNIQUE oid'	
  STATUS ON
  TYPE ANNOTATION
METADATA
  WMS_SRS 'epsg:32720'
  WMS_TITLE 'Predios_Anot'
  WMS_GROUP_TITLE 'Predios'
  WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END

PROJECTION
     	'init=epsg:32720'
END


	CLASSITEM 'activo'
	LABELITEM 'cod_uv'

      CLASS
			NAME 'Código Catastral'
      EXPRESSION (('[activo]' == '1') AND ('[cod_uv]' == '$cod_uv') AND ('[cod_man]' == '$cod_man'))
			TEXT ([cod_pred])
         LABEL
           TYPE TRUETYPE
           FONT bluehigh
           SIZE 10
           ANGLE 0
           COLOR 0 0 153 #40 40 40
           BUFFER 10
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

  CLASSITEM 'cod_geo'

      CLASS
       NAME 'Edificaciones'
			 STYLE
			    SYMBOL 'circle'
          COLOR -1 -1 -1
          OUTLINECOLOR 153 153 153 #40 40 40
			    SIZE 1
			 END
      END  # CLASS

END  # END OF LAYERFILE
#----------------------------------------------------------- Edificaciones
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
		EXPRESSION (('[numero]' == '44') AND ('[user_id]' == '$user_id'))             
		STYLE
		  SYMBOL 'circle'
      COLOR -1 -1 -1
      OUTLINECOLOR 0 0 255 #40 40 40			
	    SIZE 2
	  END
	END # end_of_class
END # end of layer object
#----------------------------------------------------------- Edificaciones-Seleccionadas
#----------------------------------------------------------- Edificaciones-Seleccionadas-Anotación
LAYER
  CONNECTIONTYPE postgis
  NAME 'edif_anot'
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
      EXPRESSION (('[numero]' == '44') AND ('[user_id]' == '$user_id'))
         LABEL
           TYPE TRUETYPE
           FONT bluehigh
           SIZE 10
           ANGLE 0
           COLOR 0 0 200 #40 40 40
           BUFFER -1
           POSITION cc
           PARTIALS FALSE
			     FORCE TRUE
         END
      END
END  # END OF LAYERFILE
#----------------------------------------------------------- Edificaciones-Seleccionadas-Anotación
#----------------------------------------------------------- Calles
LAYER
  CONNECTIONTYPE postgis 
  NAME 'Calles'
	GROUP 'Calles'
	STATUS ON
	CONNECTION 'user=$db_user password=$db_passw dbname=$db_name host=$server'
  DATA 'the_geom from temp_line USING UNIQUE oid'
  TYPE LINE
	
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'Calles'
 WMS_GROUP_TITLE 'Calles'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
#      	'init=epsg:4326'
END	

CLASSITEM 'id'
	
    CLASS
     NAME 'Calle'
      EXPRESSION (('[id]' == '1') AND ('[user_id]' == '$user_id'))
		 STYLE
		   SYMBOL 'linea'
       COLOR -1 -1 -1
	     SIZE 5
	   END
    END  # CLASS
    CLASS
     NAME 'Calle_Grande'
     EXPRESSION (('[id]' == '2') AND ('[user_id]' == '$user_id'))
		 STYLE
		   SYMBOL 'linea'
       COLOR -1 -1 -1
	     SIZE 2
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
  DATA 'the_geom from temp_line USING UNIQUE oid'
  TYPE ANNOTATION
METADATA
 WMS_SRS 'epsg:32720'
 WMS_TITLE 'calles_anot'
 WMS_GROUP_TITLE 'Calles'
 WMS_FEATURE_INFO_MIME_TYPE 'text/html'
END
	
PROJECTION
     	'init=epsg:32720'
END	

CLASSITEM 'id'
LABELITEM 'nombre'
	
    CLASS
     NAME 'Nombre'
     EXPRESSION (('[id]' == '1') AND ('[user_id]' == '$user_id'))
      LABEL
       TYPE TRUETYPE
       FONT tahoma
       SIZE 8
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
     EXPRESSION (('[id]' == '2') AND ('[user_id]' == '$user_id'))
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