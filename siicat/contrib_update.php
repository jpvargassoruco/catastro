<?php

$sql_update = "UPDATE contribuyentes SET con_pmc='$con_pmc',pmc_ant='$pmc_ant',
				con_tipo='$con_tipo',con_raz='$con_raz',con_pat='$con_pat',con_mat='$con_mat',con_nom1='$con_nom1',con_nom2='$con_nom2',con_nit='$con_nit',con_tel='$con_tel',doc_tipo='$doc_tipo',doc_num='$doc_num',doc_exp='$doc_exp', con_fech_nac='$con_fech_nac_temp',con_eciv='$con_eciv',
				dom_dpto='$dom_dpto',dom_ciu='$dom_ciu',dom_bar='$dom_bar',dom_tipo='$dom_tipo',dom_nom='$dom_nom',	dom_num='$dom_num',dom_edif='$dom_edif',dom_bloq='$dom_bloq',dom_piso='$dom_piso',dom_apto='$dom_apto',med_agu='$med_agu',med_luz='$med_luz',con_obs='$con_obs'
				WHERE id_contrib='$id_contrib_update'";
?>