<style>
	.form-container {
		max-width: 850px;
		margin: 20px auto;
		background: #fff;
		border-radius: 8px;
		box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		overflow: hidden;
	}
	
	.form-header {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		padding: 30px;
		text-align: center;
		font-size: 24px;
		font-weight: bold;
        
	}
	
	.form-body {
		padding: 30px;
	}
	
	.form-section {
		margin-bottom: 30px;
		background: #f8f9fa;
		border-left: 4px solid #667eea;
		padding: 20px;
		border-radius: 4px;
	}
	
	.section-title {
		font-size: 16px;
		font-weight: bold;
		color: #333;
		margin-bottom: 20px;
		display: flex;
		align-items: center;
	}
	
	.section-title::before {
		content: '';
		display: inline-block;
		width: 4px;
		height: 20px;
		background: #667eea;
		margin-right: 10px;
		border-radius: 2px;
	}
	
	.form-row {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
		gap: 20px;
		margin-bottom: 20px;
	}
	
	.form-group {
		display: flex;
		flex-direction: column;
	}
	
	.form-label {
		font-weight: 400;
		color: #333;
		margin-bottom: 8px;
		font-size: 13px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	
	.form-control {
		padding: 10px 12px;
		border: 1px solid #ddd;
		border-radius: 4px;
		font-size: 14px;
		transition: border-color 0.3s, box-shadow 0.3s;
		font-family: inherit;
	}
	
	.form-control:focus {
		outline: none;
		border-color: #667eea;
		box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
	}
	
	.form-control[readonly] {
		background-color: #e9ecef;
		cursor: not-allowed;
	}
	
	.form-text-info {
		font-size: 12px;
		color: #666;
		margin-top: 5px;
		font-style: italic;
	}
	
	.form-button-container {
		display: flex;
		justify-content: center;
		gap: 15px;
		margin-top: 30px;
		padding-top: 20px;
		border-top: 1px solid #ddd;
	}
	
	.btn-submit {
		background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
		color: white;
		padding: 12px 40px;
		border: none;
		border-radius: 4px;
		font-size: 16px;
		font-weight: 800;
		cursor: pointer;
		transition: transform 0.2s, box-shadow 0.2s;
	}
	
	.btn-submit:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
	}
	
	.two-column {
		grid-template-columns: repeat(2, 1fr);
	}
	
	.three-column {
		grid-template-columns: repeat(3, 1fr);
	}
	
	.four-column {
		grid-template-columns: repeat(4, 1fr);
	}
	
	.full-width {
		grid-column: 1 / -1;
	}
	
	@media (max-width: 900px) {
		.form-container {
			margin: 10px;
		}
		
		.form-body {
			padding: 20px;
		}
		
		.form-row,
		.two-column,
		.three-column,
		.four-column {
			grid-template-columns: 1fr !important;
		}
	}
</style>

<div class="form-container">
	<div class="form-header">
		Registrar Contribuyente
	</div>
	
	<div class="form-body">
		<?php	
		echo "<form name=\"form1\" method=\"post\" action=\"index.php?mod=122&id=$session_id\" accept-charset=\"utf-8\">\n";
		
		##################################################
		#------------------- P.M.C. ---------------------#
		##################################################
		?>
		
		<div class="form-section">
			<div class="section-title">Padrón Municipal</div>
			
			<?php if ($error1) { ?>
				<div class="alerta alerta-danger"><?php echo $mensaje_de_error1; ?></div>
			<?php } ?>
			
			<div class="form-row two-column">
				<div class="form-group">
					<label class="form-label">Padrón Municipal</label>
					<input type="text" name="con_pmc" class="form-control" readonly value="<?php echo $con_pmc ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Padrón Antiguo</label>
					<input type="text" name="pmc_ant" class="form-control" readonly value="<?php echo $pmc_ant ?>">
				</div>
			</div>
			<div class="form-text-info">El Padrón Municipal será asignado automáticamente por el sistema con un número correlativo</div>
		</div>
		
		<?php
		##################################################
		#------------ NOMBRE DEL CONTRIBUYENTE ----------#
		##################################################
		?>
		
		<div class="form-section">
			<div class="section-title">Nombre del Contribuyente</div>
			
			<?php if ($error2) { ?>
				<div class="alerta alerta-danger"><?php echo $mensaje_de_error2; ?></div>
			<?php } ?>
			
			<div class="form-row two-column">
				<div class="form-group">
					<label class="form-label">Tipo de Contribuyente</label>
					<select name="con_tipo" class="form-control">
						<option value="PER">Persona Natural</option>
						<option value="EMP">Persona Jurídica</option>
					</select>
				</div>
				<div class="form-group">
					<label class="form-label">Razón Social / Nombre Empresa</label>
					<input type="text" name="con_raz" class="form-control" maxlength="30" value="<?php echo $con_raz; ?>">
				</div>
				<div class="form-group">
					<label class="form-label">NIT</label>
					<input type="text" name="con_nit" class="form-control" maxlength="30" value="<?php echo $con_nit; ?>">
				</div>
			</div>
			
			<div class="form-text-info" style="margin-bottom: 15px;">Ingresar Apellido y Nombre del Contribuyente (si es empresa, ingresar el nombre del representante)</div>
			
			<div class="form-row three-column">

				<div class="form-group">
					<label class="form-label">Primer Nombre</label>
					<input type="text" name="con_nom1" class="form-control" value="<?php echo $con_nom1 ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Segundo Nombre</label>
					<input type="text" name="con_nom2" class="form-control" value="<?php echo $con_nom2 ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Apellido Paterno</label>
					<input type="text" name="con_pat" class="form-control" value="<?php echo $con_pat ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Apellido Materno</label>
					<input type="text" name="con_mat" class="form-control" value="<?php echo $con_mat ?>">
				</div>		
				<div class="form-group">
					<label class="form-label">Apellido Casada</label>
					<input type="text" name="con_cas" class="form-control" value="<?php echo $con_cas ?>">
				</div>							
			</div>
		</div>
		
		<?php
		##################################################
		#------- IDENTIFICACION DEL CONTRIBUYENTE -------#
		##################################################
		if ($error3) { ?>
			<div class="alerta alerta-danger"><?php echo $mensaje_de_error3; ?></div>
		<?php }
		?>
		
		<div class="form-section">
			<div class="section-title">Identificación del Contribuyente</div>
			
			<div class="form-row three-column">
				<div class="form-group">
					<label class="form-label">Tipo de Identificación</label>
					<?php
					$doc_tipo = trim($doc_tipo);
					$valores = get_abr('doc_tipo');
					echo "<select class=\"form-control\" name=\"doc_tipo\" size=\"1\">\n";
					foreach ($valores as $i => $j) {
						$texto = abr($valores[$i]);
						if ($valores[$i] == $doc_tipo) {
							echo "<option value=\"$valores[$i]\" selected=\"selected\">$texto</option>\n";
						} else {
							echo "<option value=\"$valores[$i]\">$texto</option>\n";
						}
					}
					echo "</select>\n";
					?>
				</div>
				<div class="form-group">
					<label class="form-label">Número de Identificación</label>
					<input type="text" name="doc_num" class="form-control" maxlength="15" value="<?php echo $doc_num ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Expedido en</label>
					<?php
					$valores = get_abr('doc_exp');
					echo "<select class=\"form-control\" name=\"doc_exp\" size=\"1\">\n";
					foreach ($valores as $i => $j) {
						$texto = abr($valores[$i]);
						if ($valores[$i] == $doc_exp) {
							echo "<option value=\"$valores[$i]\" selected=\"selected\">$texto</option>\n";
						} else {
							echo "<option value=\"$valores[$i]\">$texto</option>\n";
						}
					}
					echo "</select>\n";
					?>
				</div>
			</div>
		</div>
		
		<?php
		##################################################
		#------------------- DIRECCION ------------------#
		##################################################
		if ($error4) { ?>
			<div class="alerta alerta-danger"><?php echo $mensaje_de_error4; ?></div>
		<?php }
		?>
		
		<div class="form-section">
			<div class="section-title">Domicilio del Contribuyente</div>
			
			<div class="form-row three-column">
				<div class="form-group">
					<label class="form-label">Departamento</label>
					<?php
					$dom_dpto = trim($dom_dpto);
					$valores = get_abr('dom_dpto');
					echo "<select class=\"form-control\" name=\"dom_dpto\" size=\"1\">\n";
					foreach ($valores as $i => $j) {
						$texto = utf8_decode(abr($valores[$i]));
						if ($valores[$i] == $dom_dpto) {
							echo "<option value=\"$valores[$i]\" selected=\"selected\">$texto</option>\n";
						} else {
							echo "<option value=\"$valores[$i]\">$texto</option>\n";
						}
					}
					echo "</select>\n";
					?>
				</div>
				<div class="form-group">
					<label class="form-label">Ciudad</label>
					<input type="text" name="dom_ciu" class="form-control" maxlength="$max_strlen_ciu" value="<?php echo $dom_ciu ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Barrio</label>
					<input type="text" name="dom_bar" class="form-control" maxlength="$max_strlen_bar" value="<?php echo $dom_bar ?>">
				</div>
			</div>
			
			<div class="form-row" style="grid-template-columns: 1fr 1fr 1fr 3fr;">
				<div class="form-group">
					<label class="form-label">Tipo</label>
					<?php
					$valores = get_abr('dir_tipo');
					echo "<select class=\"form-control\" name=\"dir_tipo\" size=\"1\">\n";
					foreach ($valores as $i => $j) {
						$texto = utf8_decode(abr($valores[$i]));
						if ($valores[$i] == $dom_tipo) {
							echo "<option value=\"$valores[$i]\" selected=\"selected\">$texto</option>\n";
						} else {
							echo "<option value=\"$valores[$i]\">$texto</option>\n";
						}
					}
					echo "</select>\n";
					?>
				</div>
				<div class="form-group">
					<label class="form-label">Nombre</label>
					<?php $dir_nom_texto = textconvert($dom_nom); ?>
					<input type="text" name="dir_nom" class="form-control" maxlength="$max_strlen_dir_nom" value="<?php echo $dir_nom_texto ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Número</label>
					<input type="text" name="dir_num" class="form-control" maxlength="$max_strlen_dir_num" value="<?php echo $dom_num ?>">
				</div>
			</div>
			
			<div class="form-row" style="grid-template-columns: 1fr 1fr 1fr 1fr 1fr;">
				<div class="form-group">
					<label class="form-label">Edificio</label>
					<?php $dir_edif_texto = textconvert($dom_edif); ?>
					<input type="text" name="dir_edif" class="form-control" maxlength="$max_strlen_dir_edif" value="<?php echo $dir_edif_texto ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Bloque</label>
					<input type="text" name="dir_bloq" class="form-control" maxlength="$max_strlen_dir_bloq" value="<?php echo $dom_bloq ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Piso</label>
					<input type="text" name="dir_piso" class="form-control" maxlength="$max_strlen_dir_piso" value="<?php echo $dom_piso ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Apartamento</label>
					<input type="text" name="dir_apto" class="form-control" maxlength="$max_strlen_dir_apto" value="<?php echo $dom_apto ?>">
				</div>
			</div>
		</div>
		
		<?php
		##################################################
		#--------------- DATOS ADICIONALES --------------#
		##################################################
		?>
		
		<div class="form-section">
			<div class="section-title">Datos Adicionales</div>
			
			<div class="form-row four-column">
				<div class="form-group">
					<label class="form-label">Fecha Nacimiento</label>
					<input type="date" name="con_fecnac" class="form-control" value="<?php echo $con_fecnac ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Teléfono(s)</label>
					<input type="text" name="con_tel" class="form-control" maxlength="20" value="<?php echo $con_tel ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Actividad</label>
					<input type="text" name="med_agu" class="form-control" maxlength="10" value="<?php echo $act_con ?>">
				</div>
			</div>
		</div>
		
		<?php
		##################################################
		#----------------- OBSERVACIONES ----------------#
		##################################################?>

		<div class="form-section">
			<div class="section-title">Observaciones</div>
			
			<div class="form-group full-width">
				<label class="form-label">Observaciones Adicionales</label>
				<input type="text" name="con_obs" class="form-control" maxlength="$max_strlen_obs" value="<?php echo $con_obs ?>" placeholder="Ingrese observaciones si es necesario">
			</div>
		</div>
		<?php
		if ($error5) { ?>
			<div class="alerta alerta-danger"><?php echo $mensaje_de_error5; ?></div>
		<?php } ?>	

		<?php
		##################################################
		#----------------- BOTONES ----------------------#
		##################################################?>

		<div class="form-button-container">
			<?php
			if ($bottom) {
				echo "<button name=\"submit\" type=\"submit\" value=\"Registrar\" class=\"btn-submit\">Registrar Contribuyente</button>\n";
			}
			?>
		</div>
		
		</form>
	</div>
</div>
