<?php
### LEER DATOS DE PROPIETARIO DE INFO_INMU ###
$sql = "SELECT tit_cara, tit_1id, tit_2id, tit_3id, tit_4id, tit_5id, tit_xid, adq_modo, adq_doc, adq_fech, adq_sdoc, der_num, der_fech, ctr_obs, matricula, partida, tipo_inmu, testimonio, cod_ma_ddrr, cod_pr_ddrr FROM info_inmu WHERE cod_geo = '$cod_geo' AND id_inmu = '$id_inmu'";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);

$tipo_inmu = get_tipo_inmu_from_id_inmu ($id_inmu);
$tit_cara = utf8_decode(abr($info['tit_cara']));
if ($tit_cara == "") { $tit_cara = "---"; } 
$tit_1id = trim ($info['tit_1id']);
$tit_2id = trim ($info['tit_2id']);
$tit_3id = trim ($info['tit_3id']);
$tit_4id = trim ($info['tit_4id']);
$tit_5id = trim ($info['tit_5id']);

$cod_ma_ddrr = trim ($info['cod_ma_ddrr']);
$cod_pr_ddrr = trim ($info['cod_pr_ddrr']);

$tit_xid = trim ($info['tit_xid']);
$adq_modo = utf8_decode(abr($info['adq_modo']));
$adq_doc = $info['adq_doc']; 
$dd_rr   = $info['adq_doc']; 
$testimonio = trim ($info['testimonio']);
$matricula = trim ($info['matricula']);
$partida = trim ($info['partida']);

$adq_fech = change_date ($info['adq_fech']); 
$adq_sdoc = $info['adq_sdoc']; 
if ($adq_sdoc == "") { 
	$adq_sdoc = "---"; 
}else{
	$adq_sdoc =  number_format($adq_sdoc, 2, '.', '');
}




$der_num = $info['der_num']; 
$der_fech = change_date ($info['der_fech']); 
$ctr_obs = $info['ctr_obs'];

pg_free_result($result);

$prop1 = get_contrib_nombre ($tit_1id);
$prop2 = get_contrib_nombre ($tit_2id);
$prop3 = get_contrib_nombre ($tit_3id);
$prop4 = get_contrib_nombre ($tit_4id);
$prop5 = get_contrib_nombre ($tit_5id);

$tit_1ci = get_contrib_ci ($tit_1id);
$tit_2ci = get_contrib_ci ($tit_2id);
$tit_3ci = get_contrib_ci ($tit_3id);
$tit_4ci = get_contrib_ci ($tit_4id);
$tit_5ci = get_contrib_ci ($tit_5id);

$tit_xid = get_contrib_nombre_xid($tit_xid);

$con_tipo = get_contrib_td($tit_1id);

if ($prop1 == "-") {
   $propietario = "S/N";
} else {
   if ($tit_1ci == "-") { 
      $propietario = $prop1;
   } else {
      $propietario = $prop1." ";
   }	
	 if ($prop2 != "-") {
      $propietario = $propietario." Y ".$prop2;
      if ($tit_2ci != "-") { 
         $propietario = $propietario." ";
      } 			
   }
   if ($tit_xid != "") {
      $propietario = $propietario." *)";
   }
}

//if ($prop1 == "-") {
//   $propietario = "S/N";
//} else {
 //  if ($tit_1ci == "-") { 
    //  $propietario = $prop1;
 //  } else {
//      $propietario = $prop1." ($tit_1ci)";
//   }	
//	 if ($prop2 != "-") {
 //     $propietario = $propietario." Y ".$prop2;
 //     if ($tit_2ci != "-") { 
 //        $propietario = $propietario." ($tit_2ci)";
 //     } 			
 //  }
 //  if ($tit_xid != "") {
 //     $propietario = $propietario." *)";
 //  }
//}

$prop_string = $propietario;
$max_prop_stringlength1 = 104;
$max_prop_stringlength2 = 95;
if (strlen ($prop_string) > 100) {
   $font_size_prop = "7pt";
} elseif (strlen ($prop_string) > 75) {
   $font_size_prop = "8pt";
} else {
   $font_size_prop = "10pt";
}
### ADQ_DOC-String
$max_ancho_column = 52;
if ($adq_doc == "") { 
	$adq_doc2 = "-";
	$adq_doc1 = $adq_doc3 = "&nbsp";
} else {
	$i = $j = $max_ancho_column;
	if (strlen($adq_doc) > $i) {
		while (substr($adq_doc,$i,1) != " ") {
			$i--;
			if ($i == 0) {
				$adq_doc	= " ";
			}
		}
		$adq_doc1 = substr($adq_doc,0,$i);
		$adq_doc = substr($adq_doc,$i+1,strlen($adq_doc)-$i);
		$i = $j = $max_ancho_column;
		if (strlen($adq_doc) > $i) {
      	while (substr($adq_doc,$i,1) != " ") {
            $i--;
			      if ($i == 0) {
			         $adq_doc	= " ";
			      }
			   }
	       $adq_doc2 = substr($adq_doc,0,$i);
			   $adq_doc = substr($adq_doc,$i+1,strlen($adq_doc)-$i);	
         $i = $j = $max_ancho_column;
         if (strlen($adq_doc) > $i) {
				    $adq_doc3 = substr($adq_doc,0,$i).".";
				 } else {			 
            $adq_doc3 = "$adq_doc";
				 }
	    } else {
	       $adq_doc2 = $adq_doc;
	       $adq_doc3 = "&nbsp";		
			}	 
	 } else {
      $adq_doc1 = "&nbsp";
	    $adq_doc2 = $adq_doc;
	    $adq_doc3 = "&nbsp";
   }
}
$i = $j = $max_ancho_column = 140;



### LEER DATOS DEL PREDIO DE INFO_PREDIO ###
$sql = "SELECT	dir_zonbar, dir_tipo, dir_nom, dir_num, dir_edif, dir_cond, ser_alc, ser_agu, ser_luz, ser_tel, ser_gas, ter_topo, ter_form, ter_ubi, ter_uso, apr_pla, apr_plaobs, ter_sdoc, via_tipo, apr_plaobs, apr_plafec, apr_plahor, dir_zonurb, ter_fren, ter_con_fre, ter_fond, ter_con_fon 
		FROM info_predio 
		WHERE cod_geo = '$cod_geo' AND cod_uv = '$cod_uv' AND cod_man = '$cod_man' AND cod_pred = '$cod_pred'";
$result=pg_query($sql);
$info = pg_fetch_array($result, null, PGSQL_ASSOC);

$ter_ubi   = $info['ter_ubi']; 
$ter_form   = $info['ter_form'];

$ter_uso_tex = trim(strtoupper(abr($info['ter_uso'])));

if ($ter_uso_tex == "") {
	$ter_uso_tex = "-";
}
if ($ter_uso_tex == "TIERRA") {
	$ter_uso_tex= "TERRENO";
}

$ter_form_tex = strtoupper(abr($info['ter_form']));
if ($ter_form_tex == "") {
	$ter_form_tex = "-";
}

$english_format_number = number_format($número, 2, '.', '');

$ter_fond     = $info['ter_fond'];
$ter_con_fon  = $info['ter_con_fon'];
$ter_con_fre  =  $info['ter_con_fre'];
$ter_fren     = $info['ter_fren'];

if (strlen ($ter_fond ) > 12 OR strlen ($ter_con_fon ) > 12 OR strlen ($ter_con_fre ) > 12 OR strlen ($ter_fren ) > 12) {
   $font_size_dim = "6pt";
} else {
   $font_size_dim = "8pt";
}

$ter_sdoc   = $info['ter_sdoc'];
$barrio     = $info['dir_zonbar'];
$via_tipo   = $info['via_tipo'];
$apr_pla    = $info['apr_pla'];
$dir_zonurb = $info['dir_zonurb'];
if(empty($apr_pla)) {
	$apr_pla = "XXXX";
}

$apr_plahor = $info['apr_plahor'];
$apr_plaobs = $info['apr_plaobs'];
$apr_plafec = $info['apr_plafec'];

if ($info['dir_tipo'] == "A") {
   $dir_tipo = "AV.";
} elseif ($info['dir_tipo'] == "P") {
   $dir_tipo = "P/";
} else $dir_tipo = "C/";

$dir_nom = strtoupper($info['dir_nom']);
$direccion = $dir_nom;

if ($info['dir_num'] != "") {
   $dir_num = $info['dir_num'];
} else $dir_num = "S/N";
$direccion = $direccion." ".$dir_num;
if ($info['dir_edif'] != "") {
   $dir_edif = strtoupper(utf8_decode($info['dir_edif']));
	 $direccion = $direccion.", ECIO. ".$dir_edif;
} else $dir_edif = "-";
if ($info['dir_cond'] != "") {
   $dir_cond = strtoupper(utf8_decode($info['dir_cond']));
	 $direccion = $direccion.", COND. ".$dir_cond;
} else $dir_cond = "-";

$max_dir_stringlength1 = 85;
$max_dir_stringlength2 = 70;

if (strlen ($direccion) > $max_dir_stringlength1) {
   $font_size_dir = "7pt";
} elseif (strlen ($direccion) > $max_dir_stringlength2) {
   $font_size_dir = "8pt";
} else {
   $font_size_dir = "9pt";
}


if ($info['ser_alc'] == "") {
  $ser_alc = $ser_alc_act = "-";
} else $ser_alc = $ser_alc_act = $info['ser_alc'];
if ($info['ser_agu'] == "") {
  $ser_agu = $ser_agu_act = "-";
} else $ser_agu = $ser_agu_act = $info['ser_agu'];
if ($info['ser_luz'] == "") {
  $ser_luz = $ser_luz_act = "-";
} else $ser_luz = $ser_luz_act = $info['ser_luz'];
if ($info['ser_tel'] == "") {
  $ser_tel = $ser_tel_act = "-";
} else $ser_tel = $ser_tel_act = $info['ser_tel'];
if ($info['ser_gas'] == "") {
	$ser_gas = $ser_gas_act = "-";
  } else $ser_gas = $ser_gas_act = $info['ser_gas'];



$ter_topo_act = $info['ter_topo'];
$ter_topo = strtoupper(abr($info['ter_topo']));
if ($ter_topo == "") {
  $ter_topo = "-";
}

pg_free_result($result);
# MATERIAL DE VIA
$via_mat = edg_material_de_via ($id_inmu);
if ($via_mat == "0") {
  $via_mat_texto = "NO DEF.";
} else $via_mat_texto = strtoupper(abr($via_mat));

### LEER DATOS DE AUTORIDADES ###
$sql="SELECT puenom, pueres, pueabr FROM autoridades WHERE pueges = '$ano_actual' AND puecod = 2 ";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$puenom = 'Dra. Mariyela Soruco Peña';
} else {	
	$result=pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$puenom2 = trim ($info['puenom']);
	$pueres2 = trim ($info['pueres']);
	$pueabr2 = trim ($info['pueabr']);
}	

$sql="SELECT puenom, pueres, pueabr FROM autoridades WHERE pueges = '$ano_actual' AND puecod = 3 ";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$puenom = 'Lic. Lorenzo Cabrera';
} else {	
	$result=pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$puenom3 = trim ($info['puenom']);
	$pueres3 = trim ($info['pueres']);
	$pueabr3 = trim ($info['pueabr']);
}	

$sql="SELECT puenom, pueres, pueabr FROM autoridades WHERE pueges = '$ano_actual' AND puecod = 4 ";
$check = pg_num_rows(pg_query($sql));
if ($check == 0) {
	$puenom = 'Agrim. Ramiro Jordán Peña';
} else {	
	$result=pg_query($sql);
	$info = pg_fetch_array($result, null, PGSQL_ASSOC);
	$puenom4 = trim ($info['puenom']);
	$pueres4 = trim ($info['pueres']);
	$pueabr4 = trim ($info['pueabr']);
}	


?>
