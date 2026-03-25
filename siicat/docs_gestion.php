<?php
// Interfaz para la Gestión de Documentos de un Predio

// Incluir configuración del módulo de documentos
include_once "docs_config.php";

// Verificar que tenemos conexión a la base de datos
if (!isset($dbconn)) {
    die("Error: No se pudo establecer la conexión con la base de datos.");
}

// Obtener id_inmu desde POST o GET
if (isset($_POST['id_inmu'])) {
    $id_inmu = intval($_POST['id_inmu']);
} elseif (isset($_GET['id_inmu'])) {
    $id_inmu = intval($_GET['id_inmu']);
} else {
    die("Error: ID de inmueble no proporcionado.");
}

// Obtener folder desde POST o usar valor por defecto
$folder = isset($_POST['folder']) ? $_POST['folder'] : "mariposas";

// Obtener información básica del predio usando las funciones del sistema
$cod_cat = get_codcat_from_id_inmu($id_inmu);
$nombre_propietario = get_prop1_from_id_inmu($id_inmu);

// Verificar que el predio existe
if ($cod_cat == "-" || $cod_cat == "") {
    die("Error: No se encontró información para el predio con ID: " . htmlspecialchars($id_inmu));
}

if ($nombre_propietario == "-" || $nombre_propietario == "") {
    $nombre_propietario = "No definido";
}
// Obtener los tipos de documento de la base de datos
$sql_tipos = "SELECT * FROM tipos_documento ORDER BY nombre_documento";
$result_tipos = pg_query($dbconn, $sql_tipos);

// Lógica para manejar la subida del archivo
$mensaje_subida = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documento_escaneado'])) {
    
    $id_inmu_post = intval($_POST['id_inmu']);
    $id_tipo_documento_post = intval($_POST['id_tipo_documento']);
    $observaciones_post = pg_escape_string($dbconn, $_POST['observaciones']);
    $usuario_carga = isset($_SESSION['username']) ? $_SESSION['username'] : "admin";
    $folder_post = isset($_POST['folder']) ? pg_escape_string($dbconn, $_POST['folder']) : $folder;

    $archivo = $_FILES['documento_escaneado'];

    // --- Validación del Archivo usando funciones de configuración ---
    $errores_validacion = validar_archivo_documento($archivo);
    
    if (!empty($errores_validacion)) {
        $mensaje_subida = "<p style='color:red;'>ERRORES DE VALIDACIÓN:</p><ul>";
        foreach ($errores_validacion as $error) {
            $mensaje_subida .= "<li style='color:red;'>$error</li>";
        }
        $mensaje_subida .= "</ul>";
    } else {
        // --- Crear Directorio de Destino ---
        $directorio_predio = crear_directorio_documentos($folder_post, $id_inmu_post);
        
        if (!$directorio_predio) {
            $mensaje_subida = "<p style='color:red;'>ERROR: No se pudo crear el directorio de destino.</p>";
        } else {
            // --- Generar nombre único para el archivo ---
            $nombre_archivo_original = basename($archivo['name']);
            $nombre_archivo_guardado = generar_nombre_archivo($nombre_archivo_original);
            $ruta_archivo_guardado = $directorio_predio . '/' . $nombre_archivo_guardado;

            if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo_guardado)) {
                // --- Insertar en la Base de Datos ---
                $sql_insert = "INSERT INTO documentos_escaneados
                    (id_inmu, id_tipo_documento, nombre_archivo_original, ruta_archivo_guardado, observaciones, usuario_carga)
                    VALUES ($id_inmu_post, $id_tipo_documento_post, '$nombre_archivo_original', '$ruta_archivo_guardado', '$observaciones_post', '$usuario_carga')";
                
                $result_insert = pg_query($dbconn, $sql_insert);

                if ($result_insert) {
                    $mensaje_subida = "<p style='color:green;'>ÉXITO: El documento se ha subido y registrado correctamente.</p>";
                    $mensaje_subida .= "<p style='color:blue;'>Archivo guardado en: $ruta_archivo_guardado</p>";
                    
                    // Registrar la actividad
                    registrar_actividad_documento($dbconn, $id_inmu_post, "Documento subido", "Archivo: $nombre_archivo_original");
                } else {
                    $mensaje_subida = "<p style='color:red;'>ERROR: El archivo se subió, pero no se pudo registrar en la base de datos: " . pg_last_error($dbconn) . "</p>";
                    if (file_exists($ruta_archivo_guardado)) {
                        unlink($ruta_archivo_guardado); // Eliminar archivo si la BD falla
                    }
                }
            } else {
                $mensaje_subida = "<p style='color:red;'>ERROR: No se pudo mover el archivo subido al directorio de destino.</p>";
            }
        }
    }
}

// Obtener documentos existentes para este predio
$sql_docs = "
    SELECT d.*, t.nombre_documento
    FROM documentos_escaneados d
    JOIN tipos_documento t ON d.id_tipo_documento = t.id_tipo_documento
    WHERE d.id_inmu = $id_inmu
    ORDER BY d.fecha_presentacion DESC, d.fecha_carga DESC";
$result_docs = pg_query($dbconn, $sql_docs);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Documentos</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 85%; margin: 0 auto; }
        .header { background-color: #f2f2f2; padding: 10px; border-radius: 5px; }
        .form-section, .list-section { border: 1px solid #ccc; padding: 15px; margin-top: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Gestión de Documentos</h1>
        <p><strong>Predio:</strong> <?php echo htmlspecialchars($cod_cat); ?></p>
        <p><strong>Propietario Actual:</strong> <?php echo htmlspecialchars($nombre_propietario); ?></p>
    </div>

    <?php echo $mensaje_subida; ?>

    <!-- Sección para subir nuevos documentos -->
    <div class="form-section">
        <h2>Subir Nuevo Documento</h2>
        <form action="index.php?mod=200&id=<?php echo htmlspecialchars($session_id); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_inmu" value="<?php echo htmlspecialchars($id_inmu); ?>">
            <input type="hidden" name="folder" value="<?php echo htmlspecialchars($folder); ?>">
            
            <p>
                <label for="id_tipo_documento"><strong>Tipo de Documento:</strong></label><br>
                <select name="id_tipo_documento" id="id_tipo_documento" required style="width: 300px; padding: 5px;">
                    <option value="">-- Seleccione un tipo --</option>
                    <?php
                    if ($result_tipos) {
                        while ($tipo = pg_fetch_assoc($result_tipos)) {
                            echo '<option value="' . htmlspecialchars($tipo['id_tipo_documento']) . '">' . htmlspecialchars($tipo['nombre_documento']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="documento_escaneado"><strong>Archivo a Subir:</strong></label><br>
                <input type="file" name="documento_escaneado" id="documento_escaneado" required accept=".pdf,.jpg,.jpeg,.png" style="padding: 5px;">
                <br><small style="color: #666;">Tipos permitidos: PDF, JPG, PNG. Tamaño máximo: 10MB</small>
            </p>

            <p>
                <label for="observaciones"><strong>Observaciones:</strong></label><br>
                <textarea name="observaciones" id="observaciones" rows="3" style="width:98%; padding: 5px;" placeholder="Ingrese observaciones adicionales sobre el documento..."></textarea>
            </p>

            <p>
                <button type="submit" class="btn">Subir Documento</button>
                <button type="button" onclick="window.close();" style="margin-left: 10px; padding: 10px 15px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cerrar</button>
            </p>
        </form>
    </div>

    <!-- Sección para listar documentos existentes -->
    <div class="list-section">
        <h2>Historial de Documentos</h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo de Documento</th>
                    <th>Nombre Archivo</th>
                    <th>Fecha Presentación</th>
                    <th>Observaciones</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_docs && pg_num_rows($result_docs) > 0) {
                    while ($doc = pg_fetch_assoc($result_docs)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($doc['nombre_documento']) . '</td>';
                        echo '<td>' . htmlspecialchars($doc['nombre_archivo_original']) . '</td>';
                        echo '<td>' . htmlspecialchars($doc['fecha_presentacion']) . '</td>';
                        echo '<td>' . htmlspecialchars($doc['observaciones']) . '</td>';
                        
                        // Crear enlace directo al archivo como en el sistema de fotos
                        $ruta_archivo = $doc['ruta_archivo_guardado'];
                        if (file_exists($ruta_archivo)) {
                            // Convertir ruta física a URL web (igual que las fotos)
                            $url_documento = str_replace('C:/apache/htdocs/', "http://$server/", $ruta_archivo);
                            $url_documento = str_replace('\\', '/', $url_documento);
                            echo '<td><a href="' . htmlspecialchars($url_documento) . '" target="_blank">Ver</a></td>';
                        } else {
                            echo '<td><span style="color:red;">Archivo no encontrado</span></td>';
                        }
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No hay documentos para este predio.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>