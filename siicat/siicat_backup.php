<?php

$error = $restore = $backup = $backup_documentos = $backup_fotos = false;
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
	 pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		         VALUES ('$username','$ip','$fecha','$hora','$bkp_accion','')");				 	 
   ########################################
   #-------- CREAR ARCHIVO BACKUP --------#
   ########################################		 
   $filename = "C:/apache/siicat/backup/backup.bat";
   $content = " 
#@echo off
echo on
set CODEPAGE_DOS=850
set CODEPAGE_WIN=1252#

set PGHOST=$server
set PGDATABASE=$db_name
set PGUSER=$db_user
set PGPASSWORD=$db_passw
set PGPORT=5432
set PGCODING=utf8
c:\apache\cgi-bin\postgresql\pg_dump.exe -Fc -b %PGDATABASE% > c:\apache\siicat\backup\igm_$fecha.bkp
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
   copy("c:\\apache\\siicat\\backup\\igm_$fecha.bkp","c:\\apache\\htdocs\\tmp\\igm_$fecha.bkp"); 
	 unlink($filename);


}
################################################################################
#---------------------------------- RESTORE -----------------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Restaurar")) {	

	 include "siicat_upload_backup.php";
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
   $backup_documentos = true; 
   ########################################
   #----- CREAR LISTADO DE DOCUMENTOS ----#
   ########################################	
	 $filelist = "C:/apache/htdocs/tmp/bkp_docu.lst";
   $sql="SELECT archivo FROM imp_documentos";
   #$no_de_cambios = pg_num_rows(pg_query($sql));	
   #if ($no_de_cambios > 0) {
	 $content = "";
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
      $error = 2; 
   }
   if (!fwrite($handle, $content)) {
      $error = 3; 
   }
   fclose($handle);		
   ########################################
   #---------- COMPRIMIR ARCHIVO ---------#
   ########################################	
	 $cmd = "c:\\apache\\cgi-bin\\rar d c:/apache/htdocs/tmp/bkp_docu_".$fecha.".rar";	 
	 exec($cmd);	  
	 $cmd = "c:\\apache\\cgi-bin\\rar a -ep c:/apache/htdocs/tmp/bkp_docu_".$fecha.".rar @c:/apache/htdocs/tmp/bkp_docu.lst";	 
	 exec($cmd);	
	 unlink($filelist);	  
}
################################################################################
#------------------------------- BACKUP FOTOS ---------------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Backup Fotos")) {	
   $backup_fotos = true;
   ########################################
   #----- CREAR LISTADO DE DOCUMENTOS ----#
   ########################################	
	 $filelist = "C:/apache/htdocs/tmp/bkp_foto.lst";
   $sql="SELECT cod_cat FROM info_predio ORDER BY cod_cat";
	 $content = "";	 
   $result = pg_query($sql); 
   while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
      foreach ($line as $col_value) {
		     $filename1 = "C:/apache/htdocs/$folder/fotos/".$col_value.".jpg";
         $filename2 = "C:/apache/htdocs/$folder/fotos/".$col_value."-A.jpg";
         if (file_exists($filename1)) {
	          $content = $content."
$filename1//File";
			   }
         if (file_exists($filename2)) {
	          $content = $content."
$filename2//File";
			   }	
			}			 
   }  
   ########################################
   #----------- GUARDAR ARCHIVO ----------#
   ########################################
   if (!$handle = fopen($filelist, "w")) {
      $error = 2; 
   }
   if (!fwrite($handle, $content)) {
      $error = 3; 
   }
   fclose($handle);		
   ########################################
   #---------- COMPRIMIR ARCHIVO ---------#
   ########################################	 
	 $cmd = "c:\\apache\\cgi-bin\\rar d c:/apache/htdocs/tmp/bkp_foto_".$fecha.".rar";	 
	 exec($cmd);		 
	 $cmd = "c:\\apache\\cgi-bin\\rar a -ep c:/apache/htdocs/tmp/bkp_foto_".$fecha.".rar @c:/apache/htdocs/tmp/bkp_foto.lst";	 
	 exec($cmd);	
	 unlink($filelist);	  
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	 echo "<td>\n";
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
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait1').style.display = 'block'; }, 0);\">\n";
	 echo "         <fieldset><legend>Generando copia de seguridad de la base de datos</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
 	 echo "               <tr>\n";
 	 echo "                  <td align=\"center\" width=\"20%\">\n";
	 echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Backup\" />\n";
	 echo "                   </td>\n";
	 echo "                  <td width=\"75%\">\n";
	 if ($backup) {
	    echo "                     Base de datos del $fecha2 - $hora h. Bajar archivo <a href=\"http://$server/$folder/backup.php\">aqui</a><br />\n";	    
	 } else {
	    #echo "                     &nbsp ..............\n";
      echo "               <div id=\"wait1\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
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
      echo "			   <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\" enctype=\"multipart/form-data\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait4').style.display = 'block'; }, 0);\">\n";
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
         echo "                  <div id=\"wait4\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
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
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait2').style.display = 'block'; }, 0);\">\n";
	 echo "         <fieldset><legend>Generando copia de seguridad de los documentos</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
 	 echo "               <tr>\n";
 	 echo "                  <td align=\"center\" width=\"20%\">\n";
	 echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Backup Documentos\" />\n";
	 echo "                  </td>\n";
	 echo "                  <td width=\"75%\">\n";
	 if ($backup_documentos) {  
	    echo "                  &nbsp&nbsp Archivo del $fecha2 - $hora h.  Bajar <a href=\"http://$server/catastro_br/backup.php?mod=docs\">aqui</a><br />\n";	    
	 } else {
	    #echo "                     &nbsp ..............\n";	 
      echo "               <div id=\"wait2\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
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
	 if (($nivel == 5) AND ($backup_documentos)) {
      # Fila 7
	    echo "      <tr>\n";  
	    echo "         <td> &nbsp</td>\n";   #Col. 1                       
	    echo "         <td align=\"center\" class=\"bodyTextD\">\n";  #Col. 2	  
	    echo "            <font color=\"orange\">Para restaurar los documentos extraer el archivo RAR en<br />c:/apache/htdocs/catastro_br/documentos !</font>\n";	
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
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=91&id=$session_id\" accept-charset=\"utf-8\"  onsubmit=\"return window.setTimeout(function() { document.getElementById('wait3').style.display = 'block'; }, 0);\">\n";
	 echo "         <fieldset><legend>Generando copia de seguridad de las fotos</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
 	 echo "               <tr>\n";
 	 echo "                  <td align=\"center\" width=\"20%\">\n";
	 echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Backup Fotos\" />\n";
	 echo "                   </td>\n";
	 echo "                  <td width=\"75%\">\n";
	 if ($backup_fotos) {
	    echo "                     &nbsp&nbsp Archivo del $fecha2 - $hora h. Bajar <a href=\"http://$server/catastro_br/backup.php?mod=fotos\">aqui</a> (>115 MB)\n";	    
	 } else {
	    #echo "                     &nbsp ..............\n";
      echo "               <div id=\"wait3\" style=\"display: none;\" onclick=\"this.style.display = 'none';\">\n"; 
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
	    echo "            <font color=\"orange\">Para restaurar las fotos extraer el archivo RAR en<br />c:/apache/htdocs/catastro_br/fotos !</font>\n";	
	    echo "         </td>\n";
	    echo "         <td> &nbsp</td>\n";   #Col. 3   		
	    echo "      </tr>\n";
	 }
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
?>