<?php   #catbr_upload_documento.php



// Se ha subido un archivo?
if(!is_uploaded_file($_FILES["file1"]["tmp_name"])) {
#echo "Name: " . $_FILES["file1"]["name"] . "<br>";
#echo "Größe: " . $_FILES["file1"]["size"] . " Byte<br>";
#echo "MIME-Type: " . $_FILES["file1"]["type"] . "<br>";
#echo "Link: <a href=\"" . $_FILES["file1"]["name"] . "\">" . $_FILES["file1"]["name"] . "</a>";
   $error = true;
   $mensaje_de_error = "Error: NO ha especificado ningún archivo para subir o su tamaño excede el máximo permitido!";
} else {
   // Determinar variables
   $max_byte_size = 4000000;
   $allowed_types = "(pdf|doc)";
	 // Chequear si el archivo no es demasiado grande
   if($_FILES["file1"]["size"] > $max_byte_size) {
      $tamano_file = $_FILES["file1"]["size"];	    
      $error = true;
      $mensaje_de_error = "Error: Tamaño de archivo es $tamano_file Byte. El máximo permitido es " . $max_byte_size/1000000 . " MB!";
   // Termina bien? ($ = Al final del nombre del archivo) (/i = no importan minusculas/mayusculas)			
   } elseif (!preg_match("/\." . $allowed_types . "$/i", $_FILES["file1"]["name"])) {
      $error = true;
      $mensaje_de_error = "Error: No se pude subir el archivo. La extensión del archivo no es *.pdf!";
	 }  elseif  (strpos($_FILES["file1"]["name"],"%")) {
      $error = true;
      $mensaje_de_error = "Error: No se pude subir el archivo. Por favor, borre el símbolo '%' del nombre de archivo!";
	 } else {
      // Recortar formato
      $stringlength = strlen($_FILES["file1"]["name"]);
			$file_nombre = substr($_FILES["file1"]["name"],0,$stringlength-4);
      $file_formato = strtoupper (substr($_FILES["file1"]["name"],$stringlength-4,4));
      $file_completo = $_FILES["file1"]["name"];		
      $sql="SELECT tipo FROM imp_documentos WHERE archivo = '$file_completo'";
      $check_documentos = pg_num_rows(pg_query($sql));		
			if ($check_documentos == 1) {
         $error = true;
				 $nombre_temp = utf8_decode ($file_completo);
         $mensaje_de_error = "Error: No se pude subir el archivo. Ya existe un archivo con el nombre '$nombre_temp' en la base de datos!";																					 
      } else {
         $file_completo = utf8_decode($file_completo);	
			   $docupath = "C:/apache/htdocs/$folder/documentos/";					      
         if (move_uploaded_file($_FILES['file1']['tmp_name'], $docupath."$file_completo")) { 
#echo "Archivo subido!<br>";
         } else {
		        $error = true;
            $mensaje_de_error = "Error: No se pude subir el archivo. No hay acceso a la carpeta http://$server/$folder/documentos!";
         }
	    } 
   }		
} 
?> 
