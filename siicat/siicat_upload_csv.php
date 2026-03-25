<?php   #upload_csv.php

       // Determinar variables
       $max_byte_size = 2097152;
#       $allowed_types = "(csv|txt)";
       $allowed_types = "(csv|csv)";			 
			    // Se ha subido un archivo?
          if(is_uploaded_file($_FILES["file1"]["tmp_name"])) {
             // Termina bien? ($ = Al final del nombre del archivo) (/i = no importan minusculas/mayusculas)
             if(preg_match("/\." . $allowed_types . "$/i", $_FILES["file1"]["name"])) {
                 // Chequear si el archivo no es demasiado grande
                 if($_FILES["file1"]["size"] <= $max_byte_size) {
										$nombre_archivo = "tmp.tmp";	
										$filepath = "C:/apache/htdocs/tmp/";					      
                    if (move_uploaded_file($_FILES['file1']['tmp_name'], $filepath."$nombre_archivo")) { 
                    } else {
										   $error = true;
                       $mensaje_de_error = "Error: No se pude subir el archivo. No hay acceso a la carpeta C:/apache/htdocs/tmp!";
										} 
								 } else {
								    $error = true;
                    $mensaje_de_error = "Error: No se pude subir el archivo. El tamaño máximo permitido es " . $max_byte_size . " Byte!";
								 }
             } else {
						    $error = true;
                $mensaje_de_error = "Error: No se pude subir el archivo. La extensión del archivo no es *.csv!";
						 }
				 } else {
	           $error = true;			
             $mensaje_de_error = "NO ha especificado ningún archivo *.CSV para subir!";
				 }  
?> 
