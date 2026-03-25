<div id="dhtmlgoodies_slidedown_menu">
<ul>		
<li><br /><a href="#">Catastro Urbano</a>
<ul>
<?php 
    echo "<li $sel[1]><a href=\"index.php?mod=1&id=$session_id\">$flecha[1] Busqueda</a></li>\n";	
    echo "<li $sel[11]><a href=\"index.php?mod=11&id=$session_id\">$flecha[11] Geometría </a></li>\n"; 
    echo "<li $sel[12]><a href=\"index.php?mod=12&id=$session_id\">$flecha[12] Geometría Manzana</a></li>\n"; 
    echo "<li $sel[14]><a href=\"index.php?mod=14&id=$session_id\">$flecha[14] Geometría Predio</a></li>\n"; 
    echo "<li $sel[16]><a href=\"index.php?mod=16&id=$session_id\">$flecha[16] Geometría Edificacion</a></li>\n"; 
    echo "<li $sel[17]><a href=\"index.php?mod=17&id=$session_id\">$flecha[17] Geometría Material de Via</a></li>\n"; 
    if ($nivel == 2 OR $nivel == 5) {	 
        echo "<li $sel[25]><a href=\"index.php?mod=25&id=$session_id\">$flecha[25] Herramientas</a></li>\n"; 	
    }
?>
</ul>
</li>
<li><a href="#">Patentes</a>
<ul>
<?php     
    echo "<li $sel[101]><a href=\"index.php?mod=101&id=$session_id\">$flecha[101] Buscar</a></li>\n";	
    echo "<li $sel[102]><a href=\"index.php?mod=102&id=$session_id\">$flecha[102] Registrar</a></li>\n";	
    echo "<li $sel[104]><a href=\"index.php?mod=104&id=$session_id\">$flecha[104] Tablas de Cobro</a></li>\n";		 
    echo "<li $sel[106]><a href=\"index.php?mod=106&id=$session_id\">$flecha[106] Rubros</a></li>\n";
?>
</ul>
</li>

<li><a href="#">Contribuyentes</a>
<ul>
<?php 
	echo "<li $sel[121]><a href=\"index.php?mod=121&id=$session_id\">$flecha[121] Buscar</a></li>\n";	
	echo "<li $sel[122]><a href=\"index.php?mod=122&id=$session_id\">$flecha[122] Registrar</a></li>\n";
	echo "<li $sel[124]><a href=\"index.php?mod=124&id=$session_id\">$flecha[124] Modificar</a></li>\n";
?>
</ul>
</li>

<li><a href="#">Tasas</a>
<ul>
<?php
	echo "<li $sel[75]><a href=\"index.php?mod=75&id=$session_id\">$flecha[75] Formulario de Caja</a></li>\n";	
	echo "<li $sel[76]><a href=\"index.php?mod=76&id=$session_id\">$flecha[76] Form. Impresos</a></li>\n";
	echo "<li $sel[77]><a href=\"index.php?mod=77&id=$session_id\">$flecha[77] Tasas Administrativas</a></li>\n";	
	echo "<li $sel[78]><a href=\"index.php?mod=78&id=$session_id\">$flecha[78] Recursos </a></li>\n";	 	
?> 	 	 
</ul>
</li>	   	 

<li><a href="#">Reportes</a>
<ul>
<?php 
	echo "<li $sel[70]><a href=\"index.php?mod=70&id=$session_id\">$flecha[70] Ingresos</a></li>\n"; 	 
	echo "<li $sel[71]><a href=\"index.php?mod=71&id=$session_id\">$flecha[71] Tablas Base</a></li>\n";
	echo "<li $sel[72]><a href=\"index.php?mod=72&id=$session_id\">$flecha[72] Transferencia</a></li>\n"; 
?> 	  
</ul>
</li>  
<li><a href="#">Configuración</a>
<ul>
<?php 
	echo "<li $sel[64]><a href=\"index.php?mod=64&id=$session_id\">$flecha[64] Cotizaciones</a></li>\n";	 
	echo "<li $sel[65]><a href=\"index.php?mod=65&id=$session_id\">$flecha[65] Tablas</a></li>\n";	
	echo "<li $sel[66]><a href=\"index.php?mod=66&id=$session_id\">$flecha[66] Ajustes</a></li>\n";		  
	echo "<li $sel[59]><a href=\"index.php?mod=59&id=$session_id\">$flecha[59] Base Legal</a></li>\n";	 	
?> 	
</ul>
</li>  	 
<li><a href="#">Sistema</a>
<ul> 
<?php 
	echo "<li $sel[91]><a href=\"index.php?mod=91&id=$session_id\">$flecha[91] Copia de Seguridad</a></li>\n";
	echo "<li $sel[97]><a href=\"index.php?mod=97&id=$session_id\">$flecha[97] Cambiar Contraseña</a></li>\n";		 
	echo "<li $sel[99]><a href=\"index.php?mod=99&id=$session_id\">$flecha[99] Usuarios</a></li>\n";		
	echo "<li $sel[98]><a href=\"index.php?mod=98&id=$session_id\">$flecha[98] Ver registros</a></li>\n";	  
?>
</ul>
</li>  
</ul>
</div>