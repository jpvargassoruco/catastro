<div id=topBarTitle>GOBIERNO AUTONOMO MUNICIPAL DE <?php echo $municipio; ?></div>
<div id=topBar2>
    <div class=info id=infodata>
        <script language=JavaScript type=text/javascript> document.write(HOY);</script>

        <?php
        if ((isset($_POST["guardar"])) AND ($_POST["guardar"] == "Guardar UFV") AND (check_float($_POST["valor_nuevo"]))) {
            $ufv_de_hoy = $_POST["valor_nuevo"];
        } else {
            $ufv_de_hoy = get_coti_de_hoy($fecha, "ufv");
        }
        if ($ufv_de_hoy == 0) {
            echo "UFV:\n";
            if ($nivel >= 3) {
                echo "<a href=\"index.php?mod=64&cot=ufv&ref=$mod&id=$session_id\">Ingresar Valor</a>\n";
            } else {
                echo "--- \n";
            }
        } else {
            echo "UFV: $ufv_de_hoy\n";
        }
        echo "\n";
        $fecha_1sem = change_date($fecha_1sem_atras);
        if (!$check_backup) {
            echo "Fecha Backup: <a href=\"index.php?mod=91&id=$session_id\">Realizar Backup</a>";
        } else {
            echo "Fecha Backup: $fecha_bkp \n";
        }
        ?>

    </div>
    <div class=logout_class align=right id=logout>
        <a href=index.php?id=$session_id&logout alt='' title='Salir del programa'>Desconectar</a>
    </div>
</div>
