<?php

##############################################################################
# VALOR POR M2 * SUPERFICIE * FACTORES DEL TERRENO(SERVICIOS + INCLINACION)  #
##############################################################################
function avaluo_terreno($valor_por_m2_terr, $sup_terr, $factores_terreno)
{
   $avaluo_terreno = $valor_por_m2_terr * $sup_terr * $factores_terreno;
	 $avaluo_terreno = ROUND ($avaluo_terreno,0);
	 return $avaluo_terreno;
}

#####################################################################################
# CALIDAD DE LA CONSTRUCCION * SUPERFICIE * FACTOR DE DEPRECIACION POR ANTIGUEDAD   #
#####################################################################################
function avaluo_const($calidad_const, $area_edif_temp, $factor_deprec)
{
   $avaluo_const = $calidad_const * $area_edif_temp * $factor_deprec;
	 $avaluo_const = ROUND ($avaluo_const,0);
	 return $avaluo_const;
}

##########################################################
#   AVALUO TOTAL = AVALUO TERRENO + AVALUO CONSTRUCCION  #
##########################################################
function avaluo_total($avaluo_terreno, $avaluo_const)
{
   $avaluo_total = ROUND ($avaluo_terreno + $avaluo_const,0);
	 return $avaluo_total;
}

#####################################################################################
# IMPUESTOS NETO = (AVALUO_TOTAL - VALOR SOBRE EXCEDENTE) * VALOR MAS % + CUOTA FIJA#
#####################################################################################
# CALCULAR MANTENIMIENTO DE VALOR
#
#         UFV(FP)
# MV = (----------  - 1) * TO(Bs)
#         UFV(FV)
#
# UFV(FP) = Unidad de Fomento a la Vivienda de la fecha de pago
# TO(Bs) = Tributo omitido en bolivianos
# UFV(FV) = Unidad de Fomento a la Vivienda de la fecha de vencimiento
#####################################################################################
function calc_mant_valor($ufv_venc, $ufv_pago, $imp_neto)
{
		$mant_valor = ROUND((($ufv_pago/$ufv_venc)-1) * $imp_neto,0);
		return $mant_valor;
}

############################################################################################
#                                     n
#                          (      r  )
# INTERESES = TO(Bs)Act. * (1 + -----) - TO(Bs)Act
#                          (     360 )
#
# TO(Bs)Act = Tributo Omitido en Bolivianos Actualizado (IMP_NETO +  MANTENIMIENTO DE VALOR)
# r = Tasa de interés (Tasa activa de paridad referencial en UFV del mes en que se paga)
# n = Número de días de mora
function calc_interes($imp_neto_act, $tasa_tapr_ufv, $no_dias_de_mora)
{
		$tasa_tapr_ufv = ($tasa_tapr_ufv+3)/100;
		#$interes = $imp_neto_act * EXP(LOG(1+($tasa_tapr_ufv/360)) * $no_dias_de_mora) - $imp_neto_act;
		$interes = $imp_neto_act * EXP(LOG(1+($tasa_tapr_ufv/360)) * $no_dias_de_mora) - $imp_neto_act ;
		return ROUND($interes,0);
}


###########################################################################################
#                                     n
#                           (      r  )
# INT.Ley812 = TO(Bs)Act. * (1 + -----) - 1
#                           (     360 )
# TO(Bs)Act = Tributo Omitido en Bolivianos Actualizado (IMP_NETO +  MANTENIMIENTO DE VALOR)
# r = de 1 a 4 años     1 a 1440 dias es el 4%  
#                       1441 a 2520 dias es el 6%
#                       2521 a xxxx dias es el 10%
# n = Número de días de mora
############################################################################################
function calc_interes2016($imp_neto_act, $tasa_tapr_ufv, $no_dias_de_mora)
{


		if ($no_dias_de_mora<1440) {
			$tasa_tapr_ufv = 4;
		} elseif ($no_dias_de_mora<1440 AND $cantidad_de_dias>2520) {	  
			$tasa_tapr_ufv = 6;
		} elseif ($no_dias_de_mora>2521) { 
			$tasa_tapr_ufv = 10;
		}	

	$tasa_tapr_ufv = ($tasa_tapr_ufv)/100;
	$interes = 1 + $tasa_tapr_ufv/360;
	$interes = pow($interes, $no_dias_de_mora);
	$interes = ($interes - 1);
	$interes = $imp_neto_act * $interes;
	return ROUND($interes,5);

}

# CALCULAR CUOTAS PARA EL PLAN DE PAGO
#                     
#          n      q - 1
#    A * q    *  -------)
#                  n      
#                q   - 1 
# A = Deuda + Monto Mantenimiento de Valor - Cuota Inicial
# q = Tasa de interés por mes
# n = Número de cuotas
#
function generar_plan_de_pago ($total,$monto_mant_valor,$cuota_inicial,$no_de_cuotas,$plazo_entre_pagos,$tasa_interes)
{
    $deuda = $total + $monto_mant_valor - $cuota_inicial;
		$tasa_mes = 1+($tasa_interes*$plazo_entre_pagos/(12*100));
		$factor_amortizacion = EXP(LOG($tasa_mes) * $no_de_cuotas);
		$cuota = $deuda*$factor_amortizacion*($tasa_mes-1)/($factor_amortizacion-1);
		return $cuota;
}

?>