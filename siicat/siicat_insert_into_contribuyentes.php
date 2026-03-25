<?php

 $sql_contrib = "INSERT INTO contribuyentes 
        (id_contrib,con_pmc,pmc_ant,con_act,con_fech_ini,con_fech_fin,con_tipo,con_raz,con_pat,con_mat,
				 con_nom1,con_nom2,con_nit,con_tel,doc_tipo,doc_num,doc_exp,con_fech_nac,dom_dpto,dom_ciu, 
         dom_bar,dom_tipo,dom_nom,dom_num,dom_edif,dom_bloq,dom_piso,dom_apto,med_agu,med_luz,
         con_obs) VALUES
         ('$id_contrib','$con_pmc','$pmc_ant','$con_act','$con_fech_ini','$con_fech_fin','$con_tipo','$con_raz','$con_pat','$con_mat',
				 '$con_nom1','$con_nom2','$con_nit','$con_tel','$doc_tipo','$doc_num','$doc_exp','$con_fech_nac','$dom_dpto','$dom_ciu', 
         '$dom_bar','$dom_tipo','$dom_nom','$dom_num','$dom_edif','$dom_bloq','$dom_piso','$dom_apto','$med_agu','$med_luz',
         '$con_obs')";
#echo "SQL: $sql_contrib<br />";				 
 pg_query($sql_contrib);			 
?>