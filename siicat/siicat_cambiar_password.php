<?php

$cambiado = false; 
$error = false;

$password = $password1 = $password2 = "";

################################################################################
#----------------------------- CAMBIAR CONTRASEÑA -----------------------------#
################################################################################	
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Cambiar")) {	
	 $password = $_POST["password"];
	 $password1 = $_POST["password1"];
	 $password2 = $_POST["password2"];
	 $session_id = $_GET["id"];
	
	 $result = pg_query("SELECT password_asignado, password FROM usuarios WHERE session_id = '$session_id'");
   $password_info = pg_fetch_array($result, null, PGSQL_ASSOC);
	 $password_tabla = $password_info["password"]; 
	 $password_asignado_tabla = $password_info["password_asignado"];	 
	 pg_free_result($result);
	 $md5_password = md5($password);

	 if ($md5_password != $password_tabla) {
	    $password = "";
	    $error = true;
			$mensaje_de_error = "ERROR! Vuelva a ingresar la contraseña actual!";
	 } elseif ($password1 != $password2) {
	    $password1 = $password2 = "";
			$error = true;
			$mensaje_de_error = "ERROR! La nueva contraseña en la primera casilla no es la misma que en la segunda!";
	 } elseif (!check_string($password1)) {
	    $password1 = $password2 = ""; 
			$error = true;
			$mensaje_de_error = "ERROR! La contraseña contiene caractéres no permitidos!";		 	
	 } elseif ((strlen($password1) < 3) OR (strlen($password1) > 15)) {
	    $password1 = $password2 = "";
			$error = true;
			$mensaje_de_error = "ERROR! La contraseña tiene que tener entre 3 y 15 caractéres!";
	 } else {
	    $cambiado = true;
	    $password = md5($password1);			   
      pg_query("UPDATE usuarios SET password_asignado='XXXXXXXXXX', password='$password', fecha_password='$fecha' WHERE session_id = '$session_id'");	 
	 }
}
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
   # Fila 1
   echo "      <tr height=\"100px\">\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"60%\" class=\"pageName\">\n"; 
	 echo "            Cambiar Contraseña\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"25%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	 
   # Fila 2
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2	  
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=97&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "         <fieldset><legend>Ingrese aquí la contraseña actual y nueva</legend>\n";
	 echo "            <table width=\"90%\" border=\"0\">\n";
	 if (!$cambiado) {
 	    echo "               <tr>\n";
 	    echo "                  <td align=\"right\" width=\"40%\"><label>Contraseña Actual: </label>\n";
	    echo "                     <label for=\"label\"></label></td>\n";
	    echo "                  <td width=\"40%\"><input name=\"password\" type=\"password\" class=\"navText\" value=\"$password\"></td>\n";
	    echo "                  <td width=\"20%\" rowspan=\"3\"><div align=\"center\">\n";
	    echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" id=\"Submit\" value=\"Cambiar\" />\n";
	    echo "                   </div></td>\n";
	    echo "               </tr>\n";
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\">Contraseña Nueva:</td>\n";
	    echo "                  <td><input name=\"password1\" type=\"password\" class=\"navText\" maxlength=\"10\" value=\"$password1\"></td>\n";
	    echo "               </tr>\n";
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\">Repetir Contraseña Nueva:</td>\n";
	    echo "                  <td><input name=\"password2\" type=\"password\" class=\"navText\" maxlength=\"10\" value=\"$password2\"></td>\n";
	    echo "               </tr>\n";
	    if ($error) {
	       echo "            <tr>\n";	 
	       echo "               <td align=\"center\" height=\"30\" colspan=\"3\">\n";   #Col. 1-3 	   	 
	       echo "                  <font color=\"red\">$mensaje_de_error</font>\n";	
	       echo "               </td>\n";	
	       echo "            </tr>\n";  	 
	    }
	 } else {
 	    echo "               <tr>\n";
 	    echo "                  <td align=\"right\" height=\"25\" width=\"40%\">&nbsp</td>\n";
	    echo "                  <td width=\"40%\">&nbsp</td>\n";
	    echo "                  <td width=\"20%\">&nbsp</td>\n";
	    echo "               </tr>\n";
	    echo "               <tr>\n";
	    echo "                  <td align=\"center\" colspan=\"3\">C O N T R A S E Ñ A &nbsp&nbsp C A M B I A D A</td>\n";
	    echo "               </tr>\n";
	    echo "               <tr>\n";
	    echo "                  <td align=\"right\" height=\"25\" colspan=\"3\">&nbsp</td>\n";
	    echo "               </tr>\n";	 
	 }		  	  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td width=\"25%\" height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";	
	 echo "      <tr>\n";
   echo "         <td> &nbsp</td>\n";   #Col. 1		  
   echo "         <td align=\"center\" height=\"40\" class=\"smallText\">Por favor, mantenga su contraseña en secreto. Tenga en cuenta que el sistema diferencia entre letras mayusculas y minusculas y que la contraseña tiene que tener entre 3 y 10 caracteres.\n";   #Col. 2
   echo "         <td> &nbsp</td>\n";   #Col. 3
   echo "      </tr>\n";			 	  

	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
?>