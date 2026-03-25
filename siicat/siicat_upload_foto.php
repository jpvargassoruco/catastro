<?php  

// Se ha subido un archivo?
if(!is_uploaded_file($_FILES["file1"]["tmp_name"])) {
   $error = true;
   $mensaje_de_error = "Error: NO ha especificado ningún archivo para subir!";
} else {
   // Determinar variables
   $max_byte_size = 2000000;
   $allowed_types = "(jpg|png)";
	 // Chequear si el archivo no es demasiado grande
   if($_FILES["file1"]["size"] > $max_byte_size) {
      $tamano_file = $_FILES["file1"]["size"];	    
      $error = true;
      $mensaje_de_error = "Error: Tamaño de archivo es $tamano_file Byte. El máximo permitido es " . $max_byte_size/1000000 . " MB!";
   // Termina bien? ($ = Al final del nombre del archivo) (/i = no importan minusculas/mayusculas)			
   } elseif (!preg_match("/\." . $allowed_types . "$/i", $_FILES["file1"]["name"])) {
      $error = true;
      $mensaje_de_error = "Error: No se pude subir el archivo. La extensión del archivo no es *.jpg o *.png!";
	 } else {
      // Recortar formato
      $stringlength = strlen($_FILES["file1"]["name"]);
      $format = strtoupper (substr($_FILES["file1"]["name"],$stringlength-4,4));
      // echo $format <br>;
      if ($no_de_foto == 1) {
         $nombre_foto = $cod_cat.$format;
			} else $nombre_foto = $cod_cat."A".$format;																				 
      $fotopath = "C:/apache/htdocs/$folder/fotos/";					      
      if (move_uploaded_file($_FILES['file1']['tmp_name'], $fotopath."$nombre_foto")) { 
         // echo "Archivo subido!<br>";
      } else {
		     $error = true;
         $mensaje_de_error = "Error: No se pude subir el archivo. No hay acceso a la carpeta C:/apache/htdocs/$folder/fotos!";
      } 
   }		
} 
?> 
