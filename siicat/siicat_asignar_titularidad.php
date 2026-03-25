<?php
####################################
##### LEER DATOS DE INFO_INMU #####;
####################################
$sql="SELECT tit_cara, tit_1id, tit_2id, adq_modo, adq_doc, adq_fech, der_num, der_fech FROM info_inmu WHERE id_inmu = '$id_inmu'";
$result_tit = pg_query($sql);
$info_tit = pg_fetch_array($result_tit, null, PGSQL_ASSOC);
$tit_cara = trim($info_tit['tit_cara']);
$tit_1id = $info_tit['tit_1id'];
$tit_2id = $info_tit['tit_2id'];
$adq_modo = $info_tit['adq_modo'];
$adq_doc = trim($info_tit['adq_doc']);
$adq_fech = $info_tit['adq_fech'];
$der_num = trim($info_tit['der_num']);
$der_fech = $info_tit['der_fech'];
pg_free_result($result_tit);
if ($tit_cara == "") {
   if (($der_num != "") AND ($der_fech != "1900-01-01")) {
	    if ($tit_2id == 0) {
	       $tit_cara = "PRO";
			} else $tit_cara = "COP";
	 } elseif (($adq_doc != "") AND ($adq_fech != "1900-01-01")) {
	    $tit_cara = "POS";
	 } elseif ($tit_1id != 0) {
	    $tit_cara = "OCU";
	 } else {
	    $tit_cara = "SIN";	 
	 }
	 ##### UPDATE DATOS #####
   pg_query("UPDATE info_inmu SET tit_cara = '$tit_cara' WHERE id_inmu = '$id_inmu'");
   $inmu_con_tit++;  	 
}?>
