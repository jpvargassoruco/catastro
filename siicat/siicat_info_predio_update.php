<?php

$sql = "UPDATE info_predio SET activo = '$activo',
			   dir_tipo = '$dir_tipo', dir_nom = '$dir_nom', dir_num = '$dir_num', dir_edif = '$dir_edif', dir_cond = '$dir_cond',
				 via_tipo = '$via_tipo', via_mat = '$via_mat',		
				 ser_alc	= '$ser_alc', ser_agu	= '$ser_agu',	ser_luz	= '$ser_luz',	ser_tel	= '$ser_tel',	
				 ser_gas	= '$ser_gas',	ser_cab	= '$ser_cab',				 
				 ter_uso = '$ter_uso', ter_sdoc = '$ter_sdoc',	
				 ter_form = '$ter_form', ter_ubi = '$ter_ubi', ter_fren = '$ter_fren',
				 ter_fond = '$ter_fond', ter_nofr = '$ter_nofr', ter_san = '$ter_san',
				 ter_topo = '$ter_topo', ter_mur = '$ter_mur', 
				 ter_eesp = '$ter_eesp', ter_ace = '$ter_ace'  
			   WHERE cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";	
pg_query($sql);				 								 
?>