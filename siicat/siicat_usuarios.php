<?php
# INDICE
# CONFIGURACIONES            LINEA  20
# SUBMIT AÑADIR              LINEA  41
# AÑADIR NUEVO USUARIO       LINEA  57
# SUBMIT MODIFICAR           LINEA 103
# MODIFICAR USUARIO          LINEA 117
# SUBMIT BORRAR              LINEA 184
# BORRAR USUARIO             LINEA 202
# LISTA DE USUARIOS          LINEA 216
# FORMULARIO                 LINEA 262

################################################################################
#-------------------------- CONFIGURACIONES -----------------------------------#
################################################################################	

if (check_user_level($user_id) == 5) {
} else die ("No tiene el permiso de acceder a la página solicitada!"); 

if (isset($_GET["cod_cat"])) {
   $cod_cat = $_GET["cod_cat"];	 
}
if (isset($_POST["user_id"])) {
   $user_id = $_POST["user_id"];	
	 $user_id_set = true; 
} else { 
   $user_id = ""; 
   $user_id_set = false;
}

$esc= pg_escape_string('selected=\"selected\"');

$add_user = false;
$mod_user = false;
$delete_user = false;
$error = false;
################################################################################
#------------------------------ SUBMIT AÑADIR ---------------------------------#
################################################################################		 
	 
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "AÃ±adir")) {
   $add_user = true;
   $user_id_set = false;	 
	 $nivel = 1;
	 $usuario = "";
	 $user_id = "";
	 $password = "";
}	 
 
################################################################################
#---------------------------- AÑADIR NUEVO USUARIO ----------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "AÃ±adir nuevo Usuario")) {	 
#   $mostrar = true;
	 $nivel = $_POST["nivel"];
	 $usuario = $_POST["usuario"];
#	 $user_id = $_POST["user_id"];
	 $password1 = $_POST["password1"];
	 $password2 = $_POST["password2"];
	 
   $sql="SELECT * FROM usuarios WHERE user_id = '$user_id'";
   $check = pg_num_rows(pg_query($sql));	 	
   if ($check > 0) {
	    $usuario = utf8_decode ($usuario);
	    $password = $password1;
	    $add_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: Ya existe un usuario con ese \"User ID\"!";
	 } elseif ($password1 != $password2) {
	    $usuario = utf8_decode ($usuario);
      $password = "";
	    $add_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: La contraseña de la casilla 1 no es la misma que en la casilla 2!";
	 } elseif (!check_string($password1)) {
	    $usuario = utf8_decode ($usuario);	 
	    $add_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: La contraseña contiene caractéres no permitidos!";			 
			$password = "";
	 } elseif (!check_string($user_id)) {
	    $usuario = utf8_decode ($usuario);
			$user_id = "";	 
	    $add_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: La \"User ID\" contiene caractéres no permitidos!";			 
			$password = "";			
	 } elseif ((strlen($user_id) < 3) OR (strlen($user_id) > 12)) {
	    $usuario = utf8_decode ($usuario);
			$user_id = ""; 
	    $add_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: La \"User ID\" tiene que tener entre 3 y 12 caractéres!";
			$password = "";
	 } elseif ((strlen($password1) < 3) OR (strlen($password1) > 10)) {
	    $usuario = utf8_decode ($usuario);	 
	    $add_user = true;
			$error = true;
      $mensaje_de_error = "ERROR: La contraseña tiene que tener entre 3 y 10 caractéres!";
			$password = "";			
	 } else {
	    $password = md5($password1);
	    $sql="INSERT INTO usuarios (usuario, user_id, nivel, fecha_cuenta, password_asignado, password, fecha_password, online) 
			      VALUES ('$usuario','$user_id','$nivel','$fecha','$password1','$password','$fecha','0')";
			pg_query($sql);
	 }    	 	  
}
################################################################################
#---------------------------- SUBMIT MODIFICAR --------------------------------#
################################################################################		 
	 
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Modificar")) {
   $mod_user = true;
	 #$user_id = $_POST["user_id"];
	 $result = pg_query("SELECT * FROM usuarios WHERE user_id = '$user_id'");
   $mod_info = pg_fetch_array($result, null, PGSQL_ASSOC);
	 $nivel = $mod_info["nivel"];
	 $usuario = utf8_decode ($mod_info["usuario"]);
	 $password = $mod_info["password_asignado"];	   
	 pg_free_result($result);
}	 
################################################################################
#------------------------------ MODIFICAR USUARIO -----------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Modificar Usuario")) {	 
	 $nivel = $_POST["nivel"];
	 $usuario = $_POST["usuario"];
	 $user_id_mod = $_POST["user_id"];
	 $password1 = $_POST["password1"];
	 $password2 = $_POST["password2"];	
	 $old_user_id = $_POST["old_user_id"]; 
	 
	 $result = pg_query("SELECT nivel, usuario, password_asignado FROM usuarios WHERE user_id = '$old_user_id'");
   $mod_info = pg_fetch_array($result, null, PGSQL_ASSOC);
	 $old_nivel = $mod_info['nivel'];
	 $old_usuario = $mod_info['usuario'];
	 $old_password = $mod_info['password_asignado'];	 
	 pg_free_result($result);
	  
	 $sql="SELECT * FROM usuarios WHERE user_id = '$user_id_mod' AND user_id != '$old_user_id'";
   $check1 = pg_num_rows(pg_query($sql));	
   if ($check1 == 1) {
	    $user_id_mod = $old_user_id;
	    $password = $password1;
	    $mod_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: Ya existe un usuario con ese \"User ID\"!";
	 } elseif ($password1 != $password2) {
	    $user_id_mod = $old_user_id;
	    $mod_user = true;
			$error = true;
			$mensaje_de_error = "ERROR: La contraseña de la casilla 1 no es la misma que en la casilla 2!";
			$password = "";
	 } elseif ((!check_string($password1)) OR (!check_string($user_id))) {
	    $user_id_mod = $old_user_id;	 
	    $mod_user = true;
			$error = true;
			if (!check_string($password1)) {
			   $mensaje_de_error = "ERROR: La contraseña contiene caractéres no permitidos!";
				 $password = "";
			} else { 
			   $mensaje_de_error = "ERROR: La \"User ID\" contiene caractéres no permitidos!"; }			 
			   $password = $password1;
	 } elseif ((strlen($password1) < 3) OR (strlen($password1) > 15) OR (strlen($user_id) < 3) OR (strlen($user_id) > 15)) {
	    $user_id_mod = $old_user_id;	 
	    $mod_user = true;
			$error = true;
			if ((strlen($password1) < 3) OR (strlen($password1) > 15)) {
			   $mensaje_de_error = "ERROR: La contraseña tiene que tener entre 3 y 15 caractéres!";
				 $password = "";
			} else { 
			   $mensaje_de_error = "ERROR: La \"User ID\" tiene que tener entre 3 y 15 caractéres!"; 
				 $password = $password1;
				 }		
	 } else {
	    if ($password1 != $old_password) {	
			   $password = md5($password1);			   
			   pg_query("UPDATE usuarios SET usuario='$usuario', user_id='$user_id_mod', nivel='$nivel', password_asignado='$password1', password='$password', fecha_password='$fecha' WHERE user_id = '$old_user_id'");
      } else {
			   pg_query("UPDATE usuarios SET usuario='$usuario', user_id='$user_id_mod', nivel='$nivel'  WHERE user_id = '$old_user_id'");
			}
		  ##### REGISTRO #####
	    $username = get_username($session_id);
	    $accion = "Usuario modificado";
      pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		          VALUES ('$username','$ip','$fecha','$hora','$accion','$old_user_id')");
	 } 
}	 
################################################################################
#------------------------------- SUBMIT BORRAR --------------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Borrar")) {	
   $delete_user = true;
	 $error = true;
	 $mensaje_de_error = "Esta seguro de borrar el siguiente usuario permanentemente?";	
	 $user_id = $_POST["user_id"];
	 $result = pg_query("SELECT * FROM usuarios WHERE user_id = '$user_id'");
   $borrar_info = pg_fetch_array($result, null, PGSQL_ASSOC);
	 $nivel = $borrar_info["nivel"];
	 $usuario = utf8_decode ($borrar_info["usuario"]);
	 $fecha = $borrar_info["fecha_cuenta"];
	 $conectado = $borrar_info["online"];
   if ($conectado == "t") { $conectado = "SI"; } 
	 else { $conectado = "NO"; } 		 	 
	 pg_free_result($result);
}
################################################################################
#------------------------------ BORRAR USUARIO --------------------------------#
################################################################################	 
if ((isset($_POST["Submit"])) AND (($_POST["Submit"]) == "Borrar Usuario")) {	
   $user_id_para_borrar = $_POST["user_id"]; 
   $sql="DELETE FROM usuarios WHERE user_id = '$user_id_para_borrar'";
	 $user_id_set = false;
	 pg_query($sql);
	 ##### REGISTRO #####
	 $username = get_username($session_id);
	 $accion = "Usuario borrado";
   pg_query("INSERT INTO registro (usuario, ip, fecha, hora, accion, cod_cat) 
		          VALUES ('$username','$ip','$fecha','$hora','$accion','$user_id_para_borrar')");
}
################################################################################
#----------------------------- LISTA DE USUARIOS ------------------------------#
################################################################################	
   $sql="SELECT nivel, usuario, user_id, password_asignado, online FROM usuarios WHERE NOT usuario = 'Soporte TÃ©cnico' ORDER BY nivel, user_id";
   $usuarios_registrados = pg_num_rows(pg_query($sql));
   if ($usuarios_registrados > 0 ) {	 
      $resultado = true;
      $result = pg_query($sql);
      $i = $j = 0;
      while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
         foreach ($line as $col_value) {
		        $col_value = textconvert($col_value);	
	          if ($i == 0) {
			         $nivel_tabla[$j]  = $col_value;
			         $i++; 
            } elseif ($i == 1) {
			         $usuario_tabla[$j] = utf8_decode ($col_value);
			         $i++;
            } elseif ($i == 2) {
			         $user_id_tabla[$j] = $col_value;
			         $i++;        
						} elseif ($i == 3) {
			         $password_asignado[$j] = $col_value;
			         $i++;
						}	elseif ($i == 4) {
               if ($col_value == "t") { $conectado_tabla[$j] = "SI"; }
			         else { $conectado_tabla[$j] = "NO"; } 							
			         $i = 0;
						}
				}
	      $j++;
      } # END_OF_WHILE
      pg_free_result($result); 	 
   } # END_OF_IF
################################################################################
#---------------------------------- FORMULARIO --------------------------------#
################################################################################		
	 echo "<td>\n";
	 echo "   <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"800px\" height=\"100%\">\n";   # 3 Columnas
	 # Fila 1
   echo "      <tr height=\"40px\">\n";
	 echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "         <td align=\"center\" valign=\"center\" height=\"40\" width=\"80%\" class=\"pageName\">\n"; 
	 echo "            <br />Usuarios registrados\n";                          
   echo "         </td>\n";
	 echo "         <td width=\"15%\"> &nbsp</td>\n";   #Col. 3 			 
   echo "      </tr>\n";	 
   # Fila 2 
	 echo "      <tr>\n"; 	
	 echo "         <td> &nbsp</td>\n";   #Col. 1 		  
	 echo "         <td align=\"center\">\n";   #Col. 2
	 echo "            <table border=\"0\" align=\"center\" cellpadding=\"0\" width=\"100%\" height=\"100%\">\n";   # 3 Columnas
   echo "               <tr height=\"20px\">\n";
	 echo "                  <td width=\"5%\"> &nbsp</td>\n";   #Col. 1 	    
   echo "                  <td align=\"center\" width=\"5%\" class=\"text\">\n"; 
	 echo "                     Nivel\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" width=\"20%\" class=\"text\">\n"; 
	 echo "                     Categoría\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" width=\"35%\" class=\"text\">\n"; 
	 echo "                     Acciones permitidos\n";                          
   echo "                  </td>\n";	
   echo "                  <td align=\"center\" width=\"30%\" class=\"text\">\n"; 
	 echo "                     Impresión de...\n";                          
   echo "                  </td>\n";		  
	 echo "                   <td width=\"5%\"> &nbsp</td>\n";   #Col. 3	 
   echo "               </tr>\n";	
   echo "               <tr>\n";
	 echo "                  <td align=\"center\" colspan=\"6\"><hr width='90%'></td>\n";   #Col. 1-6
   echo "               </tr>\n";		 
   echo "               <tr height=\"20px\">\n";
	 echo "                  <td> &nbsp</td>\n";   #Col. 1 	    
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     1\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Técnico\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Ver información técnica\n";                          
   echo "                  </td>\n";	
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Informe Técnico, Mapas Temáticos\n";                          
   echo "                  </td>\n";		  
	 echo "                   <td> &nbsp</td>\n";   #Col. 3	 
   echo "               </tr>\n";		 
   echo "               <tr height=\"20px\">\n";
	 echo "                  <td> &nbsp</td>\n";   #Col. 1 	    
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     2\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Técnico Superior\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                      + Cambiar Datos y Geometría\n";                          
   echo "                  </td>\n";	
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     + Plano Catastral\n";                          
   echo "                  </td>\n";		  
	 echo "                   <td> &nbsp</td>\n";   #Col. 3	 
   echo "               </tr>\n";	
   echo "               <tr height=\"20px\">\n";
	 echo "                  <td> &nbsp</td>\n";   #Col. 1 	    
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     3\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Encargado Impuestos\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Ver información técnica, Liquidación de Impuestos\n";                          
   echo "                  </td>\n";	
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Certificado Catastral, Boletas de Pago\n";                          
   echo "                  </td>\n";		  
	 echo "                   <td> &nbsp</td>\n";   #Col. 3	 
   echo "               </tr>\n";
   echo "               <tr height=\"20px\">\n";
	 echo "                  <td> &nbsp</td>\n";   #Col. 1 	    
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     4\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Enc. Impuestos Sup.\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Modificar base de cálculo de impuestos\n";                          
   echo "                  </td>\n";	
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Todas las impresiones\n";                          
   echo "                  </td>\n";		  
	 echo "                   <td> &nbsp</td>\n";   #Col. 3	 
   echo "               </tr>\n";
   echo "               <tr height=\"20px\">\n";
	 echo "                  <td> &nbsp</td>\n";   #Col. 1 	    
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     5\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Administrador\n";                          
   echo "                  </td>\n";
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Acceso pleno, Recuperar base de datos\n";                          
   echo "                  </td>\n";	
   echo "                  <td align=\"center\" class=\"text\">\n"; 
	 echo "                     Todas las impresiones\n";                          
   echo "                  </td>\n";		  
	 echo "                   <td> &nbsp</td>\n";   #Col. 3	 
   echo "               </tr>\n";	 	 	 	  	 	  		      		  
	 echo "            </table>\n";	 
	# echo "         <td width=\"5%\"> &nbsp</td>\n";   #Col. 1 	 
	# echo "            Nivel 1: Ver información técnica, Nivel 2: Ver y modificar información técnica,\n";
	# echo "            Nivel 3: Ver información técnica e impuestos, Nivel 4: Ver información técnica e impuestos,\n";  
	# echo "            modificar base de impuestos, Nivel 5: Acceso pleno\n";
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		  
	 echo "      </tr>\n";  	
	 #############################################################################
   # Fila 2
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2  
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=99&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "         <fieldset><legend>Usuarios</legend>\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 11 Columnas
 	 echo "               <tr>\n";
	 echo "                  <td width=\"2%\"> &nbsp</td>\n"; 
	 echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextH\">Nivel</td>\n";	 
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	 
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">Nombre Completo</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextH\">ID de Usuario</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextH\">Contraseña asignada</td>\n";	
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Conectado</td>\n";	
	 echo "                  <td width=\"2%\"> &nbsp</td>\n";		 	                      
	 echo "               </tr>\n";
	 # TABLA - FILA 1-x
	 $x = $usuarios_registrados;
	 $y = 0;
	 while ($x > 0) {
	    echo "               <tr>\n";	
	    echo "                  <td>\n";	
			if ((!$user_id_set) OR ($user_id == $user_id_tabla[$y])) {
         echo "                     <input name=\"user_id\" value=\"$user_id_tabla[$y]\" type=\"radio\" checked=\"checked\">\n";
				 $user_id_set =true;
			} else {
         echo "                     <input name=\"user_id\" value=\"$user_id_tabla[$y]\" type=\"radio\">\n";
			}			
      echo "                  </td>\n";	 	 	   
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$nivel_tabla[$y]</td>\n";	 
	    echo "                  <td> &nbsp</td>\n";		   
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$usuario_tabla[$y]</td>\n";
	    echo "                  <td> &nbsp</td>\n";
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$user_id_tabla[$y]</td>\n";
	    echo "                  <td> &nbsp</td>\n"; 
 	    echo "                  <td align=\"center\" class=\"bodyTextD\">$password_asignado[$y]</td>\n";	               
	    echo "                  <td> &nbsp</td>\n";	
	    echo "                  <td align=\"center\" class=\"bodyTextD\">$conectado_tabla[$y]</td>\n";
	    echo "                  <td> &nbsp</td>\n";		  	 	 
	    echo "               </tr>\n";
			$x--;
			$y++;
	 } # END_OF_WHILE
	 echo "               <tr>\n";
   echo "                  <td align=\"center\" valign=\"center\" height=\"24px\" width=\"100%\" colspan=\"11\">\n";
   echo "                     <input type=\"submit\" name=\"Submit\" class=\"smallText\" value=\"Modificar\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";
   echo "                     <input type=\"submit\" name=\"Submit\" class=\"smallText\" value=\"Borrar\">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";
   echo "                     <input type=\"submit\" name=\"Submit\" class=\"smallText\" value=\"Añadir\">\n";			
   echo "                  </td>\n";
   echo "               </tr>\n";		  		      		  
	 echo "            </table>\n"; 
	 echo "         </fieldset>\n";
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";
if (($add_user) OR ($mod_user) OR ($delete_user)) {	
   if ($error) {
	 	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1
	   echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2 	   	 
	   echo "            <font color=\"red\">$mensaje_de_error</font>\n";	
	   echo "         </td>\n";
	   echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3 			
	 }
	 echo "      <tr>\n";  
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 1                       
	 echo "         <td valign=\"top\" class=\"bodyText\">\n";  #Col. 2  
	 echo "					<form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=99&id=$session_id\" accept-charset=\"utf-8\">\n";
	 echo "            <table border=\"0\" width=\"100%\">\n";   # 11 Columnas	 
 	 echo "               <tr>\n";
	 echo "                  <td width=\"2%\"> &nbsp</td>\n";	 
	 echo "                  <td align=\"center\" width=\"9%\" class=\"bodyTextH\">Nivel</td>\n";	 
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	 
	 echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextH\">Nombre Completo</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	 echo "                  <td align=\"center\" width=\"20%\" class=\"bodyTextH\">ID de Usuario</td>\n";
	 echo "                  <td width=\"1%\"> &nbsp</td>\n";
	 if (($add_user) OR ($mod_user)) {		 	
	   echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextH\">Contraseña</td>\n";	
	   echo "                  <td width=\"1%\"> &nbsp</td>\n"; 	
	   echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Repetir Contraseña</td>\n";	
	   echo "                  <td width=\"2%\"> &nbsp</td>\n";		 	                      
	   echo "               </tr>\n";	 
 	   echo "               <tr>\n";
	   echo "                  <td width=\"2%\"> &nbsp</td>\n";	  
	   echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextD\">\n";	
	   echo "                     <select class=\"select_grey\" name=\"nivel\" size=\"1\">\n"; 
		 $i = 1;
		 while ($i < 6){
		    if ($i == $nivel) {
	         echo "                        <option id=\"form0\" value=\"$i\" selected=\"selected\"> $i</option>\n";
				} else {
           echo "                        <option id=\"form0\" value=\"$i\"> $i</option>\n";
				}
				$i++;
		 }  	 	 
     echo "                     </select>\n";
	   echo "                	 </td>\n"; 		 
	   echo "                  <td width=\"1%\"> &nbsp</td>\n"; 	 
	   echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextD\">\n";
	   echo "                     <input type=\"text\" name=\"usuario\" id=\"form4\" value=\"$usuario\">\n";
	   echo "                	 </td>\n"; 	 
	   echo "                  <td width=\"1%\"> &nbsp</td>\n";		 	
	   echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\">\n";
	   echo "                     <input type=\"text\" name=\"user_id\" id=\"form4\" maxlength=\"12\" value=\"$user_id\">\n";
	   echo "                	 </td>\n"; 		 
	   echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	   echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\">\n";
	   echo "                     <input type=\"password\" name=\"password1\" id=\"form4\" maxlength=\"10\" value=\"$password\">\n";
	   echo "                	 </td>\n"; 	 	
	   echo "                  <td width=\"1%\"> &nbsp</td>\n";	
	   echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\">\n";
	   echo "                     <input type=\"password\" name=\"password2\" id=\"form4\" value=\"$password\">\n";
	   echo "                	 </td>\n"; 	 	
	   echo "                  <td width=\"2%\"> &nbsp</td>\n";		 	                      
	   echo "               </tr>\n";
 	   echo "               <tr>\n";
	   echo "                  <td align=\"center\" colspan=\"11\">\n";
		 if ($add_user) {		 
 	      echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Añadir nuevo Usuario\">\n";
		 } else {
        echo "                     <input type=\"hidden\" name=\"old_user_id\" value=\"$user_id\">\n"; 	      
				echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Modificar Usuario\">\n";
     }		  
	   echo "                	 </td>\n";	 	 
	   echo "               </tr>\n";
	 }else{ 	# if $delete_user)
	   echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextH\">Cuenta creada el...</td>\n";	
	   echo "                  <td width=\"1%\"> &nbsp</td>\n";	 
	   echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextH\">Conectado</td>\n";	
	   echo "                  <td width=\"2%\"> &nbsp</td>\n";		 	                      
	   echo "               </tr>\n";	 
 	   echo "               <tr>\n";
	   echo "                  <td width=\"2%\"> &nbsp</td>\n";		  
	   echo "                  <td align=\"center\" width=\"7%\" class=\"bodyTextD\"> $nivel</td>\n";
		 echo "                  <td width=\"1%\"> &nbsp</td>\n";
 	   echo "                  <td align=\"center\" width=\"30%\" class=\"bodyTextD\"> $usuario</td>\n";
	   echo "                  <td width=\"1%\"> &nbsp</td>\n";
	   echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\"> $user_id</td>\n";
		 echo "                  <td width=\"1%\"> &nbsp</td>\n";
 	   echo "                  <td align=\"center\" width=\"22%\" class=\"bodyTextD\"> $fecha</td>\n";
	   echo "                  <td width=\"1%\"> &nbsp</td>\n";		 
	   echo "                  <td align=\"center\" width=\"11%\" class=\"bodyTextD\"> $conectado</td>\n"; 	
	   echo "                  <td width=\"2%\"> &nbsp</td>\n";		 	                      
	   echo "               </tr>\n";
 	   echo "               <tr>\n";
	   echo "                  <td align=\"center\" colspan=\"11\">\n";
     echo "                     <input name=\"user_id\" type=\"hidden\" class=\"smallText\" value=\"$user_id\" />\n"; 		 		 
 	   echo "                     <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Borrar Usuario\">\n"; 
	   echo "                	 </td>\n";	 	 
	   echo "               </tr>\n";		 		 			  
	 }			 	 	 		  
	 echo "            </table>\n"; 
	 echo "         </form>\n";
	 echo "         </td>\n";
	 echo "         <td height=\"40\"> &nbsp</td>\n";   #Col. 3   		
	 echo "      </tr>\n";	
	 echo "			 <form id=\"form1\" name=\"form1\" method=\"post\" action=\"index.php?mod=99&id=$session_id\" accept-charset=\"utf-8\">\n";	 
	 echo "      <tr>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 1 	  	 
	 echo "         <td align=\"center\" height=\"40\">\n";   #Col. 2 	   
	 echo "         <input name=\"Submit\" type=\"submit\" class=\"smallText\" value=\"Volver\" />\n"; 		 
	 echo "         </td>\n";
	 echo "         <td> &nbsp</td>\n";   #Col. 3 		 
	 echo "      </tr>\n";
	 echo "      </form>\n";	
}	 	
	 # Ultima Fila
	 echo "      <tr height=\"100%\"></tr>\n";			 
	 echo "   </table>\n";
	 echo "   <br />&nbsp;<br />\n";
	 echo "</td>\n";
?>