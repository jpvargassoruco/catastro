<td>
<table border="0" align="center" cellpadding="0" width="840px" height="1200px">
	<tr height="40px">
		<td width="10%">
			<?php
			echo "&nbsp&nbsp <a href=\"index.php?mod=5&inmu=$id_inmu&calc&id=$session_id#tab-10\">\n";
			echo "<img border='0' src='http://$server/$folder/graphics/boton_atras.png'></a>\n";
			?>
		</td>
		<td align="center" valign="center" height="40" width="65%" class="pageName">Boleta de Pago - Sello</td>
		<td width="25%"> &nbsp</td>		 
	</tr>
	<tr>
		<td colspan="3" valign="top">
			<?php
			include "impuestos_generar_sello.php";
			echo "<iframe frameborder=\"1\" name=\"mapserver\" src=\"http://$server/tmp/sello$cod_cat.html\" id=\"mapserver\" width=\"840px\" height=\"1200px\" align=\"left\" scrolling=\"no\"  noresize=\"no\" marginwidth=\"0\" marginheight=\"0\">\n";
			?>
			</iframe>
		</td>
	</tr>
</table>
</td>
