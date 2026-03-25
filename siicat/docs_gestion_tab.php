<?php
// Gestión de Documentos para Tab-7
// Este archivo se incluye directamente en el tab de documentos

// Incluir configuración del módulo de documentos
include_once "docs_config.php";

// Obtener id_inmu desde POST o GET
if (isset($_POST['id_inmu'])) {
    $id_inmu_docs = intval($_POST['id_inmu']);
} elseif (isset($_GET['id_inmu'])) {
    $id_inmu_docs = intval($_GET['id_inmu']);
} else {
    $id_inmu_docs = $id_inmu; // Usar la variable del contexto principal
}

// Obtener folder desde POST o usar valor por defecto
$folder_docs = isset($_POST['folder']) ? $_POST['folder'] : $folder;

// Obtener información básica del predio usando las funciones del sistema
$cod_cat_docs = get_codcat_from_id_inmu($id_inmu_docs);
$nombre_propietario_docs = get_prop1_from_id_inmu($id_inmu_docs);

// Verificar que el predio existe
if ($cod_cat_docs == "-" || $cod_cat_docs == "") {
    echo "<p style='color:red;'>Error: No se encontró información para el predio con ID: " . htmlspecialchars($id_inmu_docs) . "</p>";
    return;
}

if ($nombre_propietario_docs == "-" || $nombre_propietario_docs == "") {
    $nombre_propietario_docs = "No definido";
}

// Obtener los tipos de documento de la base de datos
$sql_tipos = "SELECT * FROM docu_tipos ORDER BY nombre_documento";
$result_tipos = pg_query($dbconn, $sql_tipos);

// Lógica para manejar la eliminación de documentos
$mensaje_subida = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'Eliminar Documento' && isset($_POST['id_documento'])) {
    
    $id_documento_eliminar = intval($_POST['id_documento']);
    
    // Buscar información del documento
    $sql_buscar = "SELECT ruta_archivo_guardado, nombre_archivo_original, id_inmu FROM docu_escaneados WHERE id_documento = $id_documento_eliminar";
    $result_buscar = pg_query($dbconn, $sql_buscar);
    
    if ($result_buscar && pg_num_rows($result_buscar) > 0) {
        $doc_eliminar = pg_fetch_assoc($result_buscar);
        $ruta_archivo_eliminar = $doc_eliminar['ruta_archivo_guardado'];
        $nombre_archivo_eliminar = $doc_eliminar['nombre_archivo_original'];
        $id_inmu_eliminar = $doc_eliminar['id_inmu'];
        
        // Eliminar archivo físico si existe
        if (file_exists($ruta_archivo_eliminar)) {
            unlink($ruta_archivo_eliminar);
        }
        
        // Eliminar registro de la base de datos
        $sql_eliminar = "DELETE FROM docu_escaneados WHERE id_documento = $id_documento_eliminar";
        $result_eliminar = pg_query($dbconn, $sql_eliminar);
        
        if ($result_eliminar) {
            $mensaje_subida = "<p style='color:green;'>ÉXITO: El documento '$nombre_archivo_eliminar' ha sido eliminado correctamente.</p>";
            
            // Registrar la actividad
            registrar_actividad_documento($dbconn, $id_inmu_eliminar, "Documento eliminado", "Archivo: $nombre_archivo_eliminar");
        } else {
            $mensaje_subida = "<p style='color:red;'>ERROR: No se pudo eliminar el documento de la base de datos.</p>";
        }
    } else {
        $mensaje_subida = "<p style='color:red;'>ERROR: Documento no encontrado.</p>";
    }
}

// Lógica para manejar la subida del archivo (igual que el sistema de fotos)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'Subir Documento' && isset($_FILES['documento_escaneado'])) {
    
    $id_inmu_post = intval($_POST['id_inmu']);
    $id_tipo_documento_post = intval($_POST['id_tipo_documento']);
    $observaciones_post = pg_escape_string($dbconn, $_POST['observaciones']);
    $usuario_carga = isset($_SESSION['username']) ? $_SESSION['username'] : "admin";
    $folder_post = isset($_POST['folder']) ? pg_escape_string($dbconn, $_POST['folder']) : $folder_docs;

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
        echo  "folder $folder_post, inmueble $id_inmu_post, distrito $cod_dis";
        $directorio_predio = crear_directorio_documentos($folder_post, $cod_dis, $id_inmu_post);

        if (!$directorio_predio) {
            $mensaje_subida = "<p style='color:red;'>ERROR: No se pudo crear el directorio de destino.</p>";
        } else {
            // --- Generar nombre único para el archivo ---
            $nombre_archivo_original = basename($archivo['name']);
            $nombre_archivo_guardado = generar_nombre_archivo($nombre_archivo_original);
            $ruta_archivo_guardado = $directorio_predio . '/' . $nombre_archivo_guardado;

            if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo_guardado)) {
                // --- Insertar en la Base de Datos ---
                $sql_insert = "INSERT INTO docu_escaneados
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
    FROM docu_escaneados d
    JOIN tipos_documento t ON d.id_tipo_documento = t.id_tipo_documento
    WHERE d.id_inmu = $id_inmu_docs
    ORDER BY d.fecha_presentacion DESC, d.fecha_carga DESC";
$result_docs = pg_query($dbconn, $sql_docs);

?>

<style>
    .docs-container { font-family: sans-serif; }
    .docs-header { background-color: #f0f8ff; padding: 10px; margin-bottom: 15px; border-radius: 5px; border-left: 4px solid #007bff; }
    .docs-form-section, .docs-list-section { border: 1px solid #ccc; padding: 15px; margin-top: 15px; border-radius: 5px; }
    .docs-table { width: 800px; border-collapse: collapse; margin-top: 10px; }
    .docs-table th, .docs-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .docs-table th { background-color: #f2f2f2; }
    .docs-btn { padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .docs-btn:hover { background-color: #0056b3; }
</style>

<div class="docs-container">
    <h3>Gestión de Documentos <?php echo $cod_dis; ?></h3>
    
    <?php echo $mensaje_subida; ?>

    <!-- Sección para subir nuevos documentos -->
    <div class="docs-form-section">
        <h4>Subir Nuevo Documento</h4>
        <form action="index.php?mod=5&id=<?php echo htmlspecialchars($session_id); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_inmu" value="<?php echo htmlspecialchars($id_inmu_docs); ?>">
            <input type="hidden" name="folder" value="<?php echo htmlspecialchars($folder_docs); ?>">
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
                <br><small style="color: #666;">Tipos permitidos: PDF, JPG, PNG. Tamaño máximo: 5MB</small>
            </p>

            <p>
                <label for="observaciones"><strong>Observaciones:</strong></label><br>
                <textarea name="observaciones" id="observaciones" rows="3" style="width:98%; padding: 5px;" placeholder="Ingrese observaciones adicionales sobre el documento..."></textarea>
            </p>

            <p>
                <input type="hidden" name="accion" value="Subir Documento">
                <button type="submit" name="submit" value="Subir" class="docs-btn">Subir Documento</button>
            </p>
        </form>
    </div>

    <!-- Sección para listar documentos existentes -->
    <div class="docs-list-section">
        <h4>Historial de Documentos</h4>
        <table class="docs-table">
            <thead>
                <tr>
                    <th>Tipo de Documento</th>
                    <th>Nombre Archivo</th>
                    <th>Fecha Presentación</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
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
                        
                        // Crear enlaces Ver y Eliminar
                        $ruta_archivo = $doc['ruta_archivo_guardado'];
                        echo '<td>';
                        
                        // Botón Ver
                        if (file_exists($ruta_archivo)) {
                            // Convertir ruta física a URL web (igual que las fotos)
                            $url_documento = str_replace('C:/apache/htdocs/', "http://$server/", $ruta_archivo);
                            $url_documento = str_replace('\\', '/', $url_documento);
                            echo '<a href="' . htmlspecialchars($url_documento) . '" target="_blank">Ver</a>';
                        } else {
                            echo '<span style="color:red;">No encontrado</span>';
                        }
                        
                        echo ' | ';
                        
                        // Botón Eliminar
                        echo '<form style="display:inline;" method="post" onsubmit="return confirm(\'¿Está seguro de eliminar el documento: ' . htmlspecialchars($doc['nombre_archivo_original']) . '?\');">';
                        echo '<input type="hidden" name="accion" value="Eliminar Documento">';
                        echo '<input type="hidden" name="id_documento" value="' . htmlspecialchars($doc['id_documento']) . '">';
                        echo '<input type="hidden" name="id_inmu" value="' . htmlspecialchars($id_inmu_docs) . '">';
                        echo '<input type="hidden" name="folder" value="' . htmlspecialchars($folder_docs) . '">';
                        echo '<button type="submit" style="background:none; border:none; color:#dc3545; cursor:pointer; text-decoration:underline;">Eli</button>';
                        echo '</form>';
                        
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No hay documentos para este predio.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #2196F3;">
        <h4>Información del Sistema:</h4>
        <ul>
            <li>Tipos de archivos permitidos: PDF, JPG, PNG</li>
            <li>Tamaño máximo por archivo: 10 MB</li>
            <li>Los documentos se organizan por predio automáticamente</li>
            <li>Ruta de almacenamiento: C:/apache/htdocs/<?php echo htmlspecialchars($folder_docs); ?>/docs/<?php echo htmlspecialchars($id_inmu_docs); ?>/</li>
        </ul>
    </div>
</div>