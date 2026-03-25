<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	 include "c:/apache/siicat/config/config_quijarro.php"; 
	 include "c:/apache/siicat/config/constants_quijarro.php";

	 include "c:/apache/siicat/siicat_formulas.php";
	 include "c:/apache/siicat/siicat_functions.php";
	 include "c:/apache/siicat/siicat_import_excel.php"; 
	 include "c:/apache/siicat/siicat_version.php";	 
	 #echo "<pre>\n";
   #print_r($_POST);
  # echo "</pre>\n";
	 
	 if (isset($_GET['id'])) {
      $session_id = $_GET['id'];
	 } else $session_id = 0; 
#echo "Session_ID: $session_id";

	 if (check_session($session_id)) { 
	    $permiso = true;
      $nivel = check_session($session_id);
		  $user_id = get_userid ($session_id); 
	 } else {
	    $permiso = false;
	 }

################################################################################
#-------------------------------- FORMULARIO ----------------------------------#
################################################################################		 
   echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
   echo "<head>\n";
   echo "   <title>SIICAT - Sistema Integral de Catastro</title>\n";
   echo "   <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
   header("Cache-Control: no-cache, must-revalidate");
   echo "   <link rel=\"stylesheet\" href=\"css/siicat.css\" type=\"text/css\">\n";		 
?>



<?php 


$error = $error_doc = $restore = $backup = $backup_documentos = $backup_fotos = false;
################################################################################
#----------------------------------- BACKUP -----------------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Backup")) {	
   $backup = true;
   ########################################
   #---------- REGISTRAR ACCION ----------#
   ########################################	
	 $bkp_accion = "Backup Base de Datos";
	 $username = get_username($session_id);
	 pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, valor) 
		         VALUES ('$username','$ip','$fecha','$hora','$bkp_accion','')");		
   ########################################
   #------ CHEQUEAR ARCHIVO BACKUP -------#
   ########################################			
	 $backup_path = "c:/apache/siicat/backup/igm_$fecha.bkp";
	 $bkpfecha = "$fecha";
	 $i = 1;
	 $archivo_existe = true;
	 while($archivo_existe) {
      if (file_exists($backup_path)) { 
         $archivo_existe = true;
				 $backup_path = "c:/apache/siicat/backup/igm_$fecha($i).bkp";
				 $bkpfecha = "$fecha($i)";
				 $i++;
      } else $archivo_existe = false;	   						 
	 }				 		 	 
   ########################################
   #-------- CREAR ARCHIVO BACKUP --------#
   ########################################		 
   $filename = "C:/apache/siicat/backup/backup.bat";
   $content = " 
@echo off

set CODEPAGE_DOS=850
set CODEPAGE_WIN=1252#

set PGHOST=$server
set PGDATABASE=$db_name
set PGUSER=$db_user
set PGPASSWORD=$db_passw
set PGPORT=5432
set PGCODING=utf8
c:\apache\cgi-bin\postgresql\pg_dump.exe -Fc -b %PGDATABASE% > c:\apache\siicat\backup\igm_$bkpfecha.bkp
   ";

   if (!$handle = fopen($filename, "w")) {
      $error_file = 2; 
   }
   if (!fwrite($handle, $content)) {
      $error_file = 3; 
   }
   fclose($handle);
	 $cmd = "c:\\apache\\siicat\\backup\\backup.bat";
	 system($cmd);	
   copy("c:\\apache\\siicat\\backup\\igm_$bkpfecha.bkp","c:\\apache\\htdocs\\tmp\\igm_$bkpfecha.bkp"); 
	 unlink($filename);

#   putenv("PGHOST=$server");
#   putenv("PGDATABASE=$db_name");	 
#   putenv("PGUSER=$db_user");	 	  
#   putenv("PGPASSWORD=$db_passw");
#   putenv("PGPORT=5432");	 
#   putenv("PGENCODING=utf8");
}
################################################################################
#---------------------------------- RESTORE -----------------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Restaurar")) {	
	 # include "siicat_upload_backup.php";
   if(!is_uploaded_file($_FILES["file1"]["tmp_name"])) {
   $error = true;
   $mensaje_de_error = "Error: NO ha especificado ningún archivo para subir!";
   } else {
      // Determinar variables
      $max_byte_size = 9600000;
      $allowed_types = "(bkp)";
	    // Chequear si el archivo no es demasiado grande
      if($_FILES["file1"]["size"] > $max_byte_size) {
         $tamano_file = $_FILES["file1"]["size"];	    
         $error = true;
         $mensaje_de_error = "Error: Tamaño de archivo es $tamano_file Byte. El máximo permitido es " . $max_byte_size/1000000 . " MB!";
      // Termina bien? ($ = Al final del nombre del archivo) (/i = no importan minusculas/mayusculas)			
      } elseif (!preg_match("/\." . $allowed_types . "$/i", $_FILES["file1"]["name"])) {
         $error = true;
         $mensaje_de_error = "Error: No se pude subir el archivo. La extensión del archivo no es *.bkp!";
	    } else {
         // Recortar formato
         $stringlength = strlen($_FILES["file1"]["name"]);
         $format = substr($_FILES["file1"]["name"],$stringlength-4,4);
#echo $format;
         $bkp_file = "siicat_restore".$format;																			 
         $bkp_path = "C:/apache/siicat/backup/";	
			   $bkp_bkp = $bkp_path.$bkp_file;			      
         if (move_uploaded_file($_FILES['file1']['tmp_name'], $bkp_bkp)) { 
#echo "Archivo subido!<br>";
         } else {
		        $error = true;
            $mensaje_de_error = "Error: No se pude subir el archivo. No hay acceso a la carpeta C:/apache/siicat/backup!";
         } 
      }		
   } 	 
	 
	 if (!$error) {
			$restore = true;	 
      $filename = "C:/apache/siicat/backup/restore.bat";

      $content = " 
@echo off

set CODEPAGE_DOS=850
set CODEPAGE_WIN=1252#
set PGHOST=$server
set PGDATABASE=$db_name
set PGUSER=$db_user
set PGPASSWORD=$db_passw
set PGPORT=5432
set PGCODING=utf8
c:\apache\cgi-bin\postgresql\dropdb.exe %PGDATABASE% 
c:\apache\cgi-bin\postgresql\createdb.exe -E, --encoding=utf8  %PGDATABASE% 
c:\apache\cgi-bin\postgresql\pg_restore.exe -c -v -d %PGDATABASE%  c:\apache\siicat\backup\siicat_restore.bkp
      ";

      if (!$handle = fopen($filename, "w")) {
         $error_file = 2; 
      }
      if (!fwrite($handle, $content)) {
         $error_file = 3; 
      }
      fclose($handle);			
			
	    pg_close($dbconn);
	    $cmd = "c:\\apache\\siicat\\backup\\restore.bat";			
			system($cmd);
      $dbconn = pg_connect("host=$server dbname=$db_name user=$db_user password=$db_passw")
       or die('<br>Error: NO se pude conectar a la base de datos! <br>Por favor, verifique que PostgreSQL esté funcionando 
      como servicio de Windows y que la IP de la computadora esté registrada en el archivo ../postgresql/9.0/data/pg_hba.conf ' . pg_last_error());			
			$bkp_accion = "Restaurado Base de Datos";
			$username = get_username($session_id);
		  pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		              VALUES ('$username','$ip','$fecha','$hora','$bkp_accion','')");				
      $mensaje_de_restore = "Se ha restaurado la base de datos con éxito! Eventualmente tiene que ingresar al sistema nuevamente.";
			unlink($bkp_bkp);
	    unlink($filename);			
	 }    	 
}
################################################################################
#------------------------------ BACKUP DOCUMENTOS -----------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Backup Documentos")) {	
   $error_doc = false;
   $backup_documentos = true; 
   ########################################
   #----- CREAR LISTADO DE DOCUMENTOS ----#
   ########################################	
	 $filelist = "C:/apache/htdocs/tmp/bkp_docu.lst";
   $sql="SELECT archivo FROM imp_documentos";
   $no_de_archivos = pg_num_rows(pg_query($sql));	 
   if ($no_de_archivos > 0) {
	    $content = "c:/apache/htdocs/$folder/documentos/restaurar_bkp_docu.txt"; //HELP-File
      $result = pg_query($sql); 
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {	 
	          $content = $content."
c:/apache/htdocs/$folder/documentos/$col_value//File";
			   }
      }   
      ########################################
      #----------- GUARDAR ARCHIVO ----------#
      ########################################
      if (!$handle = fopen($filelist, "w")) {
         $error_doc = true;
			   $mensaje_de_error = "Error: El sistema no pudo abrir la lista de documentos. Pongase en contacto con el administrador de sistema!"; 
      }
      if (!fwrite($handle, $content)) {
         $error_doc = true;
			   $mensaje_de_error = "Error: El sistema no pudo escribir la lista de documentos! Pongase en contacto con el administrador de sistema!"; 
      }
      fclose($handle);	
			########################################
      #- CHEQUEAR SI EXISTE ARCHIVO BACKUP --#
      ########################################			
	    $backup_path = "c:/apache/siicat/backup/bkp_docu_$fecha.rar";
	    $bkpfecha = "$fecha";
	    $i = 1;
	    $archivo_existe = true;
	    while($archivo_existe) {
         if (file_exists($backup_path)) { 
            $archivo_existe = true;
						$bkpfecha = "$fecha($i)";
				    $backup_path = "c:/apache/siicat/backup/bkp_docu_$bkpfecha.rar";			    
				    $i++;
         } else $archivo_existe = false;	   						 
	    }			
      ########################################
      #---------- COMPRIMIR ARCHIVO ---------#
      ########################################	
	   # $cmd = "c:\\apache\\cgi-bin\\rar d c:/apache/htdocs/tmp/bkp_docu_".$bkpfecha.".rar";	
		  $cmd = "c:\\apache\\cgi-bin\\rar d c:/apache/siicat/backup/bkp_docu_".$bkpfecha.".rar";	  
	    exec($cmd);	  
	   # $cmd = "c:\\apache\\cgi-bin\\rar a -ep c:/apache/htdocs/tmp/bkp_docu_".$bkpfecha.".rar @c:/apache/htdocs/tmp/bkp_docu.lst";	 
	    $cmd = "c:\\apache\\cgi-bin\\rar a -ep c:/apache/siicat/backup/bkp_docu_".$bkpfecha.".rar @c:/apache/htdocs/tmp/bkp_docu.lst";	 	  
		  exec($cmd);	
	    unlink($filelist);			 
      copy("c:\\apache\\siicat\\backup\\bkp_docu_$bkpfecha.rar","c:\\apache\\htdocs\\tmp\\bkp_docu_$bkpfecha.rar"); 
   } else {
      $error_doc = true;
			$mensaje_de_error = "Error: No hay documentos en el sistema para guardar!"; 	 
	 }	  
}
################################################################################
#------------------------------- BACKUP FOTOS ---------------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Backup Fotos")) {	
   $error_foto = false;
	 $backup_fotos = true;
   ########################################
   #----- CREAR LISTADO DE DOCUMENTOS ----#
   ########################################	
	 $filelist = "C:/apache/htdocs/tmp/bkp_foto.lst";
   $sql="SELECT cod_uv, cod_man, cod_lote, cod_subl FROM info_predio ORDER BY cod_uv, cod_man, cod_lote, cod_subl";
	 $content = "c:/apache/htdocs/$folder/fotos/restaurar_bkp_fotos.txt"; //HELP-File	 
   $result = pg_query($sql); 
	 $i = 0;
	 while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
			   if ($i == 0) {
				    $cod_uv = $col_value;
				 } elseif ($i == 1) {
				    $cod_man = $col_value;
				 } elseif ($i == 2) {
				    $cod_lote = $col_value;
				 } else {
				    $cod_subl = $col_value;	
						$cod_cat = get_codcat ($cod_uv, $cod_man, $cod_lote, $cod_subl);										 
		        $filename1 = "C:/apache/htdocs/$folder/fotos/".$cod_cat.".jpg";
            $filename2 = "C:/apache/htdocs/$folder/fotos/".$cod_cat."-A.jpg";
            if (file_exists($filename1)) {
	             $content = $content."
$filename1//File";
			      }
            if (file_exists($filename2)) {
	             $content = $content."
$filename2//File";
            }
						$i = -1;
			   }	
				 $i++;
			}			 
   }  
   ########################################
   #----------- GUARDAR ARCHIVO ----------#
   ########################################
   if (!$handle = fopen($filelist, "w")) {
      $error_foto = true;
		  $mensaje_de_error = "Error: El sistema no pudo abrir la lista de fotos. Pongase en contacto con el administrador de sistema!"; 
   }
   if (!fwrite($handle, $content)) {
      $error_foto = true;
	    $mensaje_de_error = "Error: El sistema no pudo escribir la lista de fotos! Pongase en contacto con el administrador de sistema!"; 
   }
   fclose($handle);		
	 ########################################
   #- CHEQUEAR SI EXISTE ARCHIVO BACKUP --#
   ########################################			
	 $backup_path = "c:/apache/siicat/backup/bkp_fotos_$fecha.rar";
	 $bkpfecha = "$fecha";
	 $i = 1;
	 $archivo_existe = true;
	 while($archivo_existe) {
      if (file_exists($backup_path)) { 
         $archivo_existe = true;
				 $backup_path = "c:/apache/siicat/backup/bkp_fotos_$fecha($i).rar";
				 $bkpfecha = "$fecha($i)";
				 $i++;
      } else $archivo_existe = false;	   						 
	 }		 
   ########################################
   #---------- COMPRIMIR ARCHIVO ---------#
   ########################################	 
   $cmd = "c:\\apache\\cgi-bin\\rar d c:/apache/siicat/backup/bkp_fotos_".$bkpfecha.".rar";	  
	 exec($cmd);	  
	 $cmd = "c:\\apache\\cgi-bin\\rar a -ep c:/apache/siicat/backup/bkp_fotos_".$bkpfecha.".rar @c:/apache/htdocs/tmp/bkp_foto.lst";	 	  
	 exec($cmd);	
	 unlink($filelist);			 
   copy("c:\\apache\\siicat\\backup\\bkp_fotos_$bkpfecha.rar","c:\\apache\\htdocs\\tmp\\bkp_fotos_$bkpfecha.rar");  
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
 echo "</head>\n";
 echo "<body>\n";
 echo "  <div id=\"mainContent\">\n"; 
 if ($permiso) {
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            Copia de Seguridad\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";
   # Fila 2
   echo "      <tr height=\"30px\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"left\" valign=\"bottom\" class=\"bodyTextD\">\n"; 
	 echo "            Base de Datos:\n";                          
   echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";		 	 
   # Fila 3
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
#	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\" target=\"_self\">\n";
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"backup_frame.php?id=$session_id\" accept-charset=\"utf-8\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait1').style.display = 'block'; }, 0);\">\n";
	 echo "         <fieldset><legend>Generando copia de seguridad de la base de datos</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
 	 echo "               <tr>\n";
 	 echo "                  <td align=\"center\" width=\"20%\">\n";
	 echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Backup\" />\n";
	 echo "                   </td>\n";
	 echo "                  <td width=\"75%\">\n";
	 if ($backup) {
	#    echo "                     Base de datos del $fecha2 - $hora h. Bajar archivo <a href=\"http://$server/$folder/backup.php\">aqui</a><br />\n";	    
	    echo "                     Base de datos del $fecha2 - $hora h. Bajar archivo <a href=\"http://$server/tmp/igm_$bkpfecha.bkp\">aqui</a><br />\n";	
	 } else {
	    #echo "                     &nbsp ..............\n";
      echo "               <div align=\"left\" id=\"wait1\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
      echo "                  <img src=\"graphics/barra_de_progreso.gif\">\n"; 
      echo "               </div>\n"; 				
	 }
	 echo "                  </td>\n";
	 echo "                  <td width=\"5%\">\n";
	 echo "                     &nbsp\n";
	 echo "                  </td>\n";
	 echo "               </tr>\n";	  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td width=\"25%\" height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 if ($nivel == 5) {
      # Fila 4
	    echo "      <tr>\n";  
	    echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
#      echo "			   <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\">\n";	
      echo "			   <form id=\"form1\" name=\"form1\" method=\"post\" action=\"backup_frame.php?id=$session_id\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait4').style.display = 'block'; }, 0);\">\n";
	    echo "         <fieldset><legend>Recuperar base de datos desde archivo</legend>\n";
	    echo "            <table width=\"90%\" border=\"0\">\n";
 	    echo "               <tr>\n";
 	    echo "                  <td align=\"right\" colspan=\"2\">\n";
	    echo "                     Seleccionar archivo BKP:\n";
	    echo "                  </td>\n";
	    echo "                  <td>\n"; 
      echo "                     <input type=\"file\" name=\"file1\" class=\"smallText\">\n";
	    echo "                  </td>\n";
	    echo "                  <td>&nbsp</td>\n";			
	    echo "               </tr>\n";	
 	    echo "               <tr>\n";		
 	    echo "                  <td width=\"20%\" style='font-family: Tahoma; font-size: 1pt'>&nbsp</td>\n";							
			echo "                  <td width=\"20%\" style='font-family: Tahoma; font-size: 1pt'>&nbsp</td>\n";
			echo "                  <td width=\"55%\" style='font-family: Tahoma; font-size: 1pt'>&nbsp</td>\n";			
	    echo "                  <td width=\"5%\" style='font-family: Tahoma; font-size: 1pt'>&nbsp</td>\n";			
	    echo "               </tr>\n";				
 	    echo "               <tr height=\"20\">\n";		
 	    echo "                  <td align=\"center\">\n";
	    echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Restaurar\" />\n";
	    echo "                  </td>\n";							
			echo "                  <td colspan=\"2\">\n";
			if ($restore) {
	       echo "                  <font color=\"green\"> $mensaje_de_restore</font>\n";				
			} else {	
         echo "                  <div align=\"left\" id=\"wait4\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
         echo "                     <img src=\"graphics/barra_de_progreso.gif\">\n"; 
         echo "                  </div>\n";				
      }
	    echo "                  </td>\n";
	    echo "                  <td>&nbsp</td>\n";			
	    echo "               </tr>\n";	
						
	    echo "            </table>\n"; 
	    echo "         </fieldset>\n";
	    echo "         </form>\n";
	    echo "         </td>\n";
	    echo "         <td width=\"25%\" height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";
	 }
	 if ($error) {	 		 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1		  
      echo "         <td align=\"center\" height=\"40\" class=\"smallText\">\n";   #Col. 2
	    echo "            <font color=\"red\"> $mensaje_de_error</font>\n";
	    echo "         </td>\n";			 
      echo "         <td> &nbsp</td>\n";   #Col. 3
      echo "      </tr>\n";			 	  
   }	
	 /*if ($restore) {	 		 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1		  
      echo "         <td align=\"center\" height=\"40\" class=\"smallText\">\n";   #Col. 2
	    echo "            <font color=\"green\"> $mensaje_de_restore</font>\n";
	    echo "         </td>\n";			 
      echo "         <td> &nbsp</td>\n";   #Col. 3
      echo "      </tr>\n";			 	  
   }  */
	 ########################################
   # Fila 5
   echo "      <tr height=\"40px\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"left\" valign=\"bottom\" class=\"bodyTextD\">\n"; 
	 echo "            Base Legal/Documentos:\n";                          
   echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";		 	 
   # Fila 6
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
#	<form action="#" onsubmit="return window.setTimeout(function() { document.getElementById('wait').style.display = 'block'; }, 2000);">	 
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"backup_frame.php?id=$session_id\" accept-charset=\"utf-8\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait2').style.display = 'block'; }, 0);\">\n";
	 echo "         <fieldset><legend>Generando copia de seguridad de los documentos</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
 	 echo "               <tr>\n";
 	 echo "                  <td align=\"center\" width=\"20%\">\n";
	 echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Backup Documentos\" />\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"75%\">\n";
	 if (($backup_documentos) AND (!$error_doc)) {  
	    #echo "                  &nbsp&nbsp Archivo del $fecha2 - $hora h.  Bajar <a href=\"http://$server/$folder/backup.php?mod=docs\">aqui</a><br />\n";	    
	    echo "                  &nbsp&nbsp Archivo del $fecha2 - $hora h.  Bajar <a href=\"http://$server/tmp/bkp_docu_$bkpfecha.rar\">aqui</a><br />\n";
	 } else {
	    #echo "                     &nbsp ..............\n";	 
      echo "               <div align=\"left\" id=\"wait2\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
      echo "                  &nbsp&nbsp&nbsp<img src=\"graphics/barra_de_progreso.gif\">\n"; 
      echo "               </div>\n"; 	 
	 }
	 echo "                  </td>\n";
	 echo "                  <td width=\"5%\">\n";
	 echo "                     &nbsp\n";
	 echo "                  </td>\n";
	 echo "               </tr>\n";	  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td width=\"25%\" height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 if ($error_doc) {	 		 
	    echo "      <tr>\n";
      echo "         <td> &nbsp</td>\n";   #Col. 1		  
      echo "         <td align=\"center\" height=\"40\" class=\"smallText\">\n";   #Col. 2
	    echo "            <font color=\"red\"> $mensaje_de_error</font>\n";
	    echo "         </td>\n";			 
      echo "         <td> &nbsp</td>\n";   #Col. 3
      echo "      </tr>\n";			 	  
   } elseif (($nivel == 5) AND ($backup_documentos)) {
      # Fila 7
	    echo "      <tr>\n";  
	    echo "         <td> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td align=\"center\" class=\"bodyTextD\">\n";  #Col. 2	  
	    echo "            <font color=\"orange\">Para restaurar los documentos extraer el archivo RAR en<br />c:/apache/htdocs/$folder/documentos !</font>\n";	
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";
	 }	 
	 ######################################## 
   # Fila 8
   echo "      <tr height=\"40px\">\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"left\" valign=\"bottom\" class=\"bodyTextD\">\n"; 
	 echo "            Fotos:\n";                          
   echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";		 	 
   # Fila 9
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"backup_frame.php?id=$session_id\" accept-charset=\"utf-8\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait3').style.display = 'block'; }, 0);\">\n";
	 echo "         <fieldset><legend>Generando copia de seguridad de las fotos</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
 	 echo "               <tr>\n";
 	 echo "                  <td align=\"center\" width=\"20%\">\n";
	 echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Backup Fotos\" />\n";
	 echo "                   </td>\n";
	 echo "                  <td width=\"75%\">\n";
	 if ($backup_fotos) {
	    echo "                     &nbsp&nbsp Archivo del $fecha2 - $hora h. Bajar <a href=\"http://$server/tmp/bkp_fotos_$bkpfecha.rar\">aqui</a> (>115 MB)\n";	    
	 } else {
	    #echo "                     &nbsp ..............\n";
      echo "               <div align=\"left\" id=\"wait3\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
      echo "                  &nbsp&nbsp&nbsp<img src=\"graphics/barra_de_progreso.gif\">\n"; 
      echo "               </div>\n"; 	 			
	 }
	 echo "                  </td>\n";
	 echo "                  <td width=\"5%\">\n";
	 echo "                     &nbsp\n";
	 echo "                  </td>\n";
	 echo "               </tr>\n";	  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td width=\"25%\" height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
	 if (($nivel == 5) AND ($backup_fotos)) {
      # Fila 7
	    echo "      <tr>\n";  
	    echo "         <td> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td align=\"center\" class=\"bodyTextD\">\n";  #Col. 2	  
	    echo "            <font color=\"orange\">Para restaurar las fotos extraer el archivo RAR en<br />c:/apache/htdocs/$folder/fotos !</font>\n";	
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";
	 }
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
} else { # END_OF_IF --> permiso
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	    echo "      <tr height=\"100\">\n";  
	    echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td width=\"55%\" align=\"left\" class=\"bodyTextD\">\n";  #Col. 2	  
	    echo "            Error: No tiene los permisos para ver la pagina!\n";	
	    echo "         </td>\n";
	    echo "         <td width=\"40%\"> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";
		 echo "   </table>\n";		
}
   echo " </div>\n";
 echo " </body>\n";
 echo " </html>\n"; 	 
	 
?>